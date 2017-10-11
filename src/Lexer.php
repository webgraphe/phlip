<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing\CloseListSymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseMapSymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseVectorSymbol;
use Webgraphe\Phlip\Symbol\DotSymbol;
use Webgraphe\Phlip\Symbol\KeywordSymbol;
use Webgraphe\Phlip\Symbol\Mark\TildeSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenMapSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenVectorSymbol;
use Webgraphe\Phlip\Symbol\Mark\GraveAccentSymbol;
use Webgraphe\Phlip\Symbol\Mark\StraightSingleMarkSymbol;

class Lexer
{
    /** @var string[] */
    const COLLECTION_DELIMITERS = [
        OpenListSymbol::CHARACTER => OpenListSymbol::class,
        CloseListSymbol::CHARACTER => CloseListSymbol::class,
        OpenVectorSymbol::CHARACTER => OpenVectorSymbol::class,
        CloseVectorSymbol::CHARACTER => CloseVectorSymbol::class,
        OpenMapSymbol::CHARACTER => OpenMapSymbol::class,
        CloseMapSymbol::CHARACTER => CloseMapSymbol::class,
    ];

    /** @var string[] */
    const ESCAPED_CHARACTERS = [
        'n' => "\n",
        'r' => "\r",
        't' => "\t",
    ];

    /** @var callable|null */
    private $lexemeFilter;

    /**
     * @param callable|null $lexemeFilter An array_filter() callback used to filter tokenized LexemeContract instances.
     */
    public function __construct(callable $lexemeFilter = null)
    {
        if (!func_num_args()) {
            $lexemeFilter = $this->getDefaultLexemeFilter();
        }
        $this->lexemeFilter = $lexemeFilter;
    }

    /**
     * Filters comments out.
     *
     * @return callable
     */
    protected function getDefaultLexemeFilter(): callable
    {
        return function (LexemeContract $lexeme) {
            return !($lexeme instanceof Comment);
        };
    }

    /**
     * @param string $source
     * @param string|null $name
     * @return LexemeStream
     * @throws LexerException
     */
    public function parseSource(string $source, string $name = null): LexemeStream
    {
        $stream = CharacterStream::fromString($source, $name);

        $lexemes = [];
        try {
            while ($stream->valid()) {
                $current = $stream->current();
                switch(true) {
                    case $this->isWhitespace($current):
                        break;
                    case $this->isCollectionDelimiter($current):
                        $lexemes[] = call_user_func([self::COLLECTION_DELIMITERS[$current], 'instance']);
                        break;
                    case StraightSingleMarkSymbol::CHARACTER === $current:
                        $lexemes[] = StraightSingleMarkSymbol::instance();
                        break;
                    case GraveAccentSymbol::CHARACTER === $current:
                        $lexemes[] = GraveAccentSymbol::instance();
                        break;
                    case TildeSymbol::CHARACTER === $current:
                        $lexemes[] = TildeSymbol::instance();
                        break;
                    case Comment::DELIMITER === $current:
                        $lexemes[] = $this->parseComment($stream);
                        break;
                    case StringAtom::DELIMITER === $current:
                        $lexemes[] = $this->parseString($stream);
                        break;
                    default:
                        $lexemes[] = $this->parseWord($stream);
                        break;
                }

                $stream->next();
            }
        } catch (Exception $e) {
            throw new LexerException("Failed parsing source", 0, $e);
        }

        if ($this->lexemeFilter) {
            $lexemes = array_filter($lexemes, $this->lexemeFilter);
        }

        return LexemeStream::fromLexemes(...$lexemes);
    }

    protected function isCollectionDelimiter($character): bool
    {
        return array_key_exists($character, self::COLLECTION_DELIMITERS);
    }

    protected function isWhiteSpace($character): bool
    {
        return ctype_space($character);
    }

    protected function replaceEscapedCharacter($character): string
    {
        return self::ESCAPED_CHARACTERS[$character] ?? $character;
    }

    protected function parseString(CharacterStream $stream): StringAtom
    {
        $anchor = new CodeAnchor($stream);
        $string = '';
        while (StringAtom::DELIMITER !== ($character = $stream->next()->current())) {
            if ("\\" === $character) {
                $character = $this->replaceEscapedCharacter($character);
            }
            $string .= $character;
        }

        return StringAtom::fromString($string, $anchor);
    }

    protected function parseComment(CharacterStream $stream): Comment
    {
        $comment = '';
        while ($stream->next()->valid() && "\n" !== $stream->current()) {
            $comment .= $stream->current();
        }

        return new Comment($comment);
    }

    protected function parseWord(CharacterStream $stream): LexemeContract
    {
        $anchor = new CodeAnchor($stream);
        $word = '';
        while ($stream->valid()) {
            $character = $stream->current();
            if ($this->isWhiteSpace($character) || $this->isCollectionDelimiter($character)) {
                $stream->previous();
                break;
            }
            $stream->next();
            $word .= $character;
        }

        switch (true) {
            case DotSymbol::CHARACTER === $word:
                return DotSymbol::instance();
            case $word && KeywordSymbol::CHARACTER === $word[0]:
                return KeywordAtom::fromString($word, $anchor);
            case NumberAtom::isNumber($word):
                return NumberAtom::fromString($word, $anchor);
            default:
                return IdentifierAtom::fromString($word, $anchor);
        }
    }
}

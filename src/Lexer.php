<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing\CloseListSymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseMapSymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseVectorSymbol;
use Webgraphe\Phlip\Symbol\DotSymbol;
use Webgraphe\Phlip\Symbol\KeywordSymbol;
use Webgraphe\Phlip\Symbol\Mark\UnquoteSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenMapSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenVectorSymbol;
use Webgraphe\Phlip\Symbol\Mark\QuasiquoteSymbol;
use Webgraphe\Phlip\Symbol\Mark\QuoteSymbol;

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
        $this->lexemeFilter = func_num_args() < 2 ? $this->getDefaultLexemeFilter() : $lexemeFilter;
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
     * @throws Exception\LexerException
     */
    public function parseSource(string $source, string $name = null): LexemeStream
    {
        $stream = CharacterStream::fromString($source, $name);

        $lexemes = [];
        try {
            while ($stream->valid()) {
                if ($lexeme = $this->extractLexeme($stream)) {
                    $lexemes[] = $lexeme;
                }
                $stream->next();
            }
        } catch (PhlipException $e) {
            throw new Exception\LexerException("Failed parsing source", 0, $e);
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

    /**
     * @param CharacterStream $stream
     * @param string $delimiter
     * @return StringAtom
     * @throws Exception\StreamException
     */
    protected function parseString(CharacterStream $stream, string $delimiter): StringAtom
    {
        $anchor = new CodeAnchor($stream);
        $string = '';
        while ($delimiter !== ($character = $stream->next()->current())) {
            if (StringAtom::ESCAPE_CHARACTER === $character) {
                $character = $this->replaceEscapedCharacter($stream->next()->current());
            }
            $string .= $character;
        }

        return StringAtom::fromString($string, $anchor);
    }

    /**
     * @param CharacterStream $stream
     * @return Comment
     * @throws Exception\StreamException
     */
    protected function parseComment(CharacterStream $stream): Comment
    {
        $comment = '';
        while ($stream->next()->valid() && "\n" !== $stream->current() && "\r" !== $stream->current()) {
            $comment .= $stream->current();
        }

        return new Comment($comment);
    }

    /**
     * @param CharacterStream $stream
     * @return LexemeContract
     * @throws Exception\AssertionException
     * @throws Exception\StreamException
     */
    protected function parseWord(CharacterStream $stream): LexemeContract
    {
        $anchor = new CodeAnchor($stream);
        $word = $this->extractWord($stream);

        if (DotSymbol::CHARACTER === $word) {
            return DotSymbol::instance();
        }

        if ($word && KeywordSymbol::CHARACTER === $word[0]) {
            return KeywordAtom::fromString($word, $anchor);
        }

        if (NumberAtom::isNumber($word)) {
            return NumberAtom::fromString($word, $anchor);
        }

        return IdentifierAtom::fromString($word, $anchor);
    }

    /**
     * @param CharacterStream $stream
     * @return null|LexemeContract
     * @throws Exception\AssertionException
     * @throws Exception\StreamException
     */
    protected function extractLexeme(CharacterStream $stream): ?LexemeContract
    {
        $current = $stream->current();
        if ($this->isWhitespace($current)) {
            return null;
        }

        if ($this->isCollectionDelimiter($current)) {
            return call_user_func([self::COLLECTION_DELIMITERS[$current], 'instance']);
        }

        if (QuoteSymbol::CHARACTER === $current) {
            return QuoteSymbol::instance();
        }

        if (QuasiquoteSymbol::CHARACTER === $current) {
            return QuasiquoteSymbol::instance();
        }

        if (UnquoteSymbol::CHARACTER === $current) {
            return UnquoteSymbol::instance();
        }

        if (Comment::DELIMITER === $current) {
            return $this->parseComment($stream);
        }

        if (StringAtom::DELIMITER === $current) {
            return $this->parseString($stream, $current);
        }

        return $this->parseWord($stream);
    }

    /**
     * @param CharacterStream $stream
     * @return string
     * @throws Exception\StreamException
     */
    protected function extractWord(CharacterStream $stream): string
    {
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

        return $word;
    }
}

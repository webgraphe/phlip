<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing\ClosePairSymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseVectorSymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseMapSymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseListSymbol;
use Webgraphe\Phlip\Symbol\KeywordSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenPairSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenVectorSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenMapSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\QuoteSymbol;

class Lexer
{
    const SPECIAL_CHARACTERS = [
        ' ',
        "\n",
        "\t",
        OpenListSymbol::CHARACTER,
        CloseListSymbol::CHARACTER,
        OpenVectorSymbol::CHARACTER,
        CloseVectorSymbol::CHARACTER,
    ];

    /**
     * @param string $source
     * @return LexemeStream
     * @throws LexerException
     */
    public function parseSource(string $source): LexemeStream
    {
        $stream = CharacterStream::fromString($source);

        $lexemes = [];
        try {
            while ($stream->valid()) {
                switch ($stream->current()) {
                    case ' ':
                    case "\n":
                    case "\t":
                        continue;
                    case QuoteSymbol::instance()->getValue():
                        $lexemes[] = QuoteSymbol::instance();
                        break;
                    case KeywordSymbol::instance()->getValue():
                        $lexemes[] = KeywordSymbol::instance();
                        break;
                    case OpenListSymbol::instance()->getValue():
                        $lexemes[] = OpenListSymbol::instance();
                        break;
                    case CloseListSymbol::instance()->getValue():
                        $lexemes[] = CloseListSymbol::instance();
                        break;
                    case OpenVectorSymbol::instance()->getValue():
                        $lexemes[] = OpenVectorSymbol::instance();
                        break;
                    case CloseVectorSymbol::instance()->getValue():
                        $lexemes[] = CloseVectorSymbol::instance();
                        break;
                    case OpenMapSymbol::instance()->getValue():
                        $lexemes[] = OpenMapSymbol::instance();
                        break;
                    case CloseMapSymbol::instance()->getValue():
                        $lexemes[] = CloseMapSymbol::instance();
                        break;
                    case ';':
                        $lexemes[] = $this->parseComment($stream);
                        break;
                    case '"':
                        $lexemes[] = $this->parseString($stream);
                        break;
                    default:
                        $lexeme = $this->parseWord($stream);
                        switch (true) {
                            case NumberAtom::isNumber($lexeme):
                                $lexemes[] = new NumberAtom($lexeme);
                                break;
                            default:
                                $lexemes[] = IdentifierAtom::fromString($lexeme);
                                break;
                        }
                        break;
                }
                $stream->next();
            }
        } catch (Exception $e) {
            throw new LexerException("Failed parsing source", 0, $e);
        }

        return LexemeStream::fromLexemes(...$lexemes);
    }

    private function parseString(Stream $stream): StringAtom
    {
        $string = '';
        while (true) {
            $character = $stream->next()->current();
            if ('"' === $character) {
                break;
            }
            if ("\\" === $character) {
                switch ($character = $stream->next()->current()) {
                    case 'n':
                        $character = "\n";
                        break;
                    case 'r':
                        $character = "\r";
                        break;
                    case 't':
                        $character = "\t";
                        break;
                }
            }
            $string .= $character;
        }

        return new StringAtom($string);
    }

    private function parseComment(Stream $stream): Comment
    {
        $comment = '';
        while ($stream->valid() && "\n" !== $stream->current()) {
            $comment .= $stream->next()->current();
        }

        return new Comment($comment);
    }

    private function parseWord(Stream $stream): string
    {
        $word = '';
        while ($stream->valid()) {
            $character = $stream->current();
            if (in_array($character, self::SPECIAL_CHARACTERS)) {
                $stream->previous();
                break;
            }
            $stream->next();
            $word .= $character;
        }

        return $word;
    }
}

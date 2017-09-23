<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\BooleanAtom;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NullAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\CloseListSymbol;
use Webgraphe\Phlip\Symbol\OpenListSymbol;
use Webgraphe\Phlip\Symbol\QuoteSymbol;

class Lexer
{
    /**
     * @param string $source
     * @return LexemeStream
     * @throws LexerException
     */
    public function parseSource(string $source): LexemeStream
    {
        $stream = CharacterStream::fromString($source);

        $lexemes = [];
        while ($stream->valid()) {
            switch ($stream->current()) {
                case ' ':
                case "\n":
                case "\t":
                    continue;
                case QuoteSymbol::CHARACTER:
                    $lexemes[] = QuoteSymbol::instance();
                    break;
                case OpenListSymbol::CHARACTER:
                    $lexemes[] = OpenListSymbol::instance();
                    break;
                case CloseListSymbol::CHARACTER:
                    $lexemes[] = CloseListSymbol::instance();
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
                        case NullAtom::isNull($lexeme):
                            $lexemes[] = NullAtom::instance();
                            break;
                        case BooleanAtom::isBoolean($lexeme):
                            $lexemes[] = 'true' === $lexeme ? BooleanAtom::true() : BooleanAtom::false();
                            break;
                        case NumberAtom::isNumber($lexeme):
                            $lexemes[] = new NumberAtom($lexeme);
                            break;
                        default:
                            $lexemes[] = new IdentifierAtom($lexeme);
                    }
                    break;
            }
            $stream->next();
        }

        return LexemeStream::fromLexemes(...$lexemes);
    }

    private function parseString(Stream $stream): StringAtom
    {
        $string = '';
        while (true) {
            $stream->next();
            $character = $stream->current();
            if ('"' === $character) {
                break;
            }
            if ("\\" === $character) {
                $stream->next();
                switch ($character = $stream->current()) {
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
            $stream->next();
            $comment .= $stream->current();
        }

        return new Comment($comment);
    }

    private function parseWord(Stream $stream): string
    {
        $word = '';
        while ($stream->valid()) {
            $character = $stream->current();
            if (in_array($character, [' ', "\n", "\t", ")", "("])) {
                $stream->previous();
                break;
            }
            $stream->next();
            $word .= $character;
        }

        return $word;
    }
}

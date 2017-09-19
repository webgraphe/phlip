<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\LiteralAtom;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Symbol\CloseDelimiterSymbol;
use Webgraphe\Phlip\Symbol\OpenDelimiterSymbol;
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
        $stream = new CharacterStream($source);

        $lexemes = [];
        while ($stream->isValid()) {
            switch ($stream->current()) {
                case ' ':
                case "\n":
                case "\t":
                    continue;
                case QuoteSymbol::CHARACTER:
                    $lexemes[] = QuoteSymbol::instance();
                    break;
                case OpenDelimiterSymbol::CHARACTER:
                    $lexemes[] = OpenDelimiterSymbol::instance();
                    break;
                case CloseDelimiterSymbol::CHARACTER:
                    $lexemes[] = CloseDelimiterSymbol::instance();
                    break;
                case ';':
                    $lexemes[] = $this->parseComment($stream);
                    break;
                case '"':
                    $lexemes[] = $this->parseString($stream);
                    break;
                default:
                    $lexeme = $this->parseWord($stream);
                    $lexemes[] = is_numeric($lexeme) ? new LiteralAtom($lexeme) : new IdentifierAtom($lexeme);
                    break;
            }
            $stream->next();
        }

        return new LexemeStream(...$lexemes);
    }

    private function parseString(CharacterStream $stream): LiteralAtom
    {
        $string = '';
        while (true) {
            $character = $stream->next()->current();
            if ('"' === $character) {
                break;
            }
            if ("\\" === $character) {
                $character = $stream->next()->current();
                switch ($character) {
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

        return new LiteralAtom($string);
    }

    private function parseComment(CharacterStream $stream): Comment
    {
        $comment = '';
        while ($stream->isValid() && "\n" !== $stream->current()) {
            $comment .= $stream->next()->current();
        }

        return new Comment($comment);
    }

    private function parseWord(CharacterStream $stream): string
    {
        $word = '';
        while ($stream->isValid()) {
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

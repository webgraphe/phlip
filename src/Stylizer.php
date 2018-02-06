<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class Stylizer
{
    /** @var callable|null */
    private $formatter;

    public function __construct(callable $formatter = null)
    {
        $this->formatter = $formatter ?? static::cliFormatter();
    }

    public static function cliFormatter(): \Closure
    {
        return function (LexemeContract $lexeme): string {
            if ($lexeme instanceof Symbol) {
                return "\033[1;37m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof NumberAtom) {
                return "\033[1;36m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof StringAtom) {
                return "\033[1;34m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof IdentifierAtom) {
                return "\033[1;33m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof KeywordAtom) {
                return "\033[1;32m{$lexeme}\033[0m";
            }

            return (string)$lexeme;
        };
    }

    /**
     * @param string $source
     * @param Lexer|null $lexer
     * @return string
     * @throws Exception\LexerException
     * @throws Exception\StreamException
     */
    public function stylizeSource(string $source, Lexer $lexer = null): string
    {
        $lexer = $lexer ?? new Lexer;

        return $this->stylizeLexemeStream($lexer->parseSource($source));
    }

    /**
     * @param LexemeStream $stream
     * @return string
     * @throws Exception\StreamException
     */
    public function stylizeLexemeStream(LexemeStream $stream): string
    {
        $top = 0;
        $stack = [[]];
        while ($stream->valid()) {
            $lexeme = $stream->current();
            $formattedLexeme = $this->formatLexeme($lexeme);
            $afresh = (0 === $top && !$stack[$top]) || 1 === count($stack[$top]);
            if ($lexeme instanceof Opening) {
                $stack[] = [];
                ++$top;
                $stack[$top][] = $afresh ? $formattedLexeme : " $formattedLexeme";
            } elseif ($lexeme instanceof Closing) {
                $stack[$top][] = $formattedLexeme;
                $popped = array_pop($stack);
                --$top;
                $stack[$top][] = implode('', $popped);
            } else {
                $stack[$top][] = $afresh ? $formattedLexeme : " $formattedLexeme";
            }
            $stream->next();
        }

        return implode('', $stack[$top]);
    }

    protected function formatLexeme(LexemeContract $lexeme): string
    {
        return $this->formatter ? call_user_func($this->formatter, $lexeme) : (string)$lexeme;
    }
}

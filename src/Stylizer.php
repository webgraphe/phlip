<?php

namespace Webgraphe\Phlip;

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
        $this->formatter = $formatter;
    }

    public function stylizeSource(string $source, Lexer $lexer = null): string
    {
        $lexer = $lexer ?? new Lexer;

        return $this->stylizeLexemeStream($lexer->parseSource($source));
    }

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

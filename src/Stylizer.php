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

    public function stylizeLexemeStream(LexemeStream $stream): string
    {
        $top = 0;
        $stack = [[]];
        while ($stream->valid()) {
            $lexeme = $stream->current();
            $formattedLexeme = $this->formatLexeme($lexeme);
            $afresh = (0 === $top && !$stack[$top]) || 1 === count($stack[$top]);
            switch (true) {
                case $lexeme instanceof Opening:
                    $stack[] = [];
                    ++$top;
                    $stack[$top][] = $afresh ? $formattedLexeme : " $formattedLexeme";
                    break;

                case $lexeme instanceof Closing:
                    $stack[$top][] = $formattedLexeme;
                    $popped = array_pop($stack);
                    --$top;
                    $stack[$top][] = implode('', $popped);
                    break;

                default:
                    $stack[$top][] = $afresh ? $formattedLexeme : " $formattedLexeme";
                    break;
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

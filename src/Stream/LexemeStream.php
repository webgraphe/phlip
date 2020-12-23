<?php

namespace Webgraphe\Phlip\Stream;

use Closure;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Contracts\StringConvertibleContract;
use Webgraphe\Phlip\Exception\StreamException;
use Webgraphe\Phlip\Stream;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

/**
 * @method LexemeContract[] content()
 */
class LexemeStream extends Stream implements StringConvertibleContract
{
    /** @var Closure|null */
    private $lexemeStylizer = null;

    /**
     * @param LexemeContract ...$lexemes
     * @return static
     */
    public static function fromLexemes(LexemeContract ...$lexemes): self
    {
        return new static($lexemes, count($lexemes));
    }

    public function withLexemeStylizer(callable $lexemeStylizer): LexemeStream
    {
        $stream = clone $this;
        $stream->lexemeStylizer = $lexemeStylizer;

        return $stream;
    }

    /**
     * @return LexemeContract
     * @throws StreamException
     */
    public function current(): LexemeContract
    {
        return parent::current();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $output = '';
        try {
            $this->rewind();
            while ($this->valid()) {
                $output .= $this->toString();
            }
        } catch (StreamException $e) {
            return "ERROR: " . $e->getMessage();
        }

        return $output;
    }

    /**
     * @return string
     * @throws StreamException
     */
    protected function toString(): string
    {
        $output = '';

        if (($opening = $this->current()) instanceof Opening) {
            $items = [];
            $this->next();
            while (!(($closing = $this->current()) instanceof Closing)) {
                $items[] = $this->toString();
            }
            $content = '';
            if ($items) {
                if ($opening instanceof Opening\OpenMapSymbol) {
                    $items = array_map(
                        function (array $pair) {
                            return implode(' ', $pair);
                        },
                        array_chunk($items, 2)
                    );
                }
                $content = str_replace(
                        PHP_EOL,
                        PHP_EOL . '    ',
                        PHP_EOL . implode(PHP_EOL, $items)
                    )
                    . PHP_EOL;
            }
            $output .= $this->stylizeLexeme($opening) . $content . $this->stylizeLexeme($closing);
        } else {
            $output .= $this->stylizeLexeme($this->current());
        }

        $this->next();

        return $output;
    }

    protected function stylizeLexeme(LexemeContract $lexeme): string
    {
        return $this->lexemeStylizer ? call_user_func($this->lexemeStylizer, $lexeme) : (string)$lexeme;
    }
}

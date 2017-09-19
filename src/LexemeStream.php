<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\LexemeContract;

class LexemeStream implements \Countable
{
    /** @var LexemeContract[] */
    private $stream;
    private $cursor = 0;
    private $length = 0;

    public function __construct(LexemeContract ...$lexemes)
    {
        $this->stream = $lexemes;
        $this->length = count($lexemes);
    }

    public function current(): ?LexemeContract
    {
        if ($this->isValid()) {
            return $this->stream[$this->cursor];
        }

        throw new \RuntimeException("Unexpected end of stream");
    }

    public function next()
    {
        ++$this->cursor;
    }

    public function isValid(): bool
    {
        return $this->cursor < $this->length;
    }

    public function count(): int
    {
        return $this->length;
    }
}

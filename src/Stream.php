<?php

namespace Webgraphe\Phlip;

abstract class Stream implements \Iterator
{
    /** @var string */
    private $stream;
    /** @var int */
    private $cursor = 0;
    /** @var int */
    private $length = 0;
    /** @var string */
    private $current;

    protected function __construct($stream, int $length)
    {
        $this->stream = $stream;
        $this->length = $length;
        $this->updateCurrent();
    }

    public function key(): int
    {
        return $this->cursor;
    }

    public function current()
    {
        if (null === $this->current) {
            throw new \OutOfBoundsException;
        }

        return $this->current;
    }

    public function next()
    {
        ++$this->cursor;
        $this->updateCurrent();
    }

    public function previous()
    {
        --$this->cursor;
        $this->updateCurrent();
    }

    public function valid(): bool
    {
        return $this->cursor >= 0 && $this->cursor < $this->length;
    }

    public function rewind()
    {
        $this->cursor = 0;
        $this->updateCurrent();
    }

    private function updateCurrent()
    {
        $this->current = $this->valid() ? $this->stream[$this->cursor] : null;
    }
}

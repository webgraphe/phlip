<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Exception\StreamException;

abstract class Stream implements \Iterator
{
    /** @var mixed */
    protected $stream;
    /** @var int */
    protected $length = 0;
    /** @var string|null */
    protected $name;

    /** @var int */
    private $cursor = 0;
    /** @var string */
    private $current;

    final protected function __construct($stream, int $length, string $name = null)
    {
        $this->stream = $stream;
        $this->length = $length;
        $this->name = $name;
        $this->updateCurrent();
    }

    public function key(): int
    {
        return $this->cursor;
    }

    /**
     * @return mixed|string
     * @throws StreamException
     */
    public function current()
    {
        if (null === $this->current) {
            throw new StreamException('Out of bounds');
        }

        return $this->current;
    }

    /**
     * @return static
     */
    public function next(): Stream
    {
        ++$this->cursor;
        $this->updateCurrent();

        return $this;
    }

    /**
     * @return static
     */
    public function previous(): Stream
    {
        --$this->cursor;
        $this->updateCurrent();

        return $this;
    }

    public function valid(): bool
    {
        return $this->cursor >= 0 && $this->cursor < $this->length;
    }

    /**
     * @return static
     */
    public function rewind(): Stream
    {
        $this->cursor = 0;
        $this->updateCurrent();

        return $this;
    }

    /**
     * @return mixed
     */
    public function content()
    {
        return $this->stream;
    }

    private function updateCurrent()
    {
        $this->current = $this->valid() ? $this->stream[$this->cursor] : null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}

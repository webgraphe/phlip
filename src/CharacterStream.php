<?php

namespace Webgraphe\Phlip;

class CharacterStream
{
    /** @var string */
    private $stream;
    /** @var int */
    private $cursor = 0;
    /** @var int */
    private $length = 0;
    /** @var string */
    private $current;

    public function __construct(string $string)
    {
        $this->stream = str_replace(["\r\n", "\r"], ["\n", "\n"], $string);
        $this->length = strlen($this->stream);
        if ($this->isValid()) {
            $this->current = $this->stream[$this->cursor];
        }
    }

    public function current(): ?string
    {
        if (null !== $this->current) {
            return $this->current;
        }

        throw new \RuntimeException('Unexpected end of stream');
    }

    public function next(): CharacterStream
    {
        ++$this->cursor;
        $this->current = $this->isValid() ? $this->stream[$this->cursor] : null;

        return $this;
    }

    public function previous(): CharacterStream
    {
        --$this->cursor;
        $this->current = $this->isValid() ? $this->stream[$this->cursor] : null;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->cursor < $this->length;
    }
}

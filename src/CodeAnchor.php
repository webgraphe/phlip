<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Stream\CharacterStream;

class CodeAnchor implements CodeAnchorContract
{
    /** @var CharacterStream */
    private $stream;
    /** @var int */
    private $offset;

    public function __construct(CharacterStream $stream, int $offset = null)
    {
        $this->stream = $stream;
        $this->offset = $offset ?? $stream->key();
    }

    public function getSourceName(): ?string
    {
        return $this->stream->getName();
    }

    public function getCode(): ?string
    {
        return $this->stream->content();
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}

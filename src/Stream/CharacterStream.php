<?php

namespace Webgraphe\Phlip\Stream;

use Webgraphe\Phlip\Stream;

class CharacterStream extends Stream
{
    public static function fromString(string $stream)
    {
        $stream = str_replace(["\r\n", "\r"], ["\n", "\n"], $stream);

        return new static($stream, strlen($stream));
    }

    public function current(): string
    {
        return parent::current();
    }
}

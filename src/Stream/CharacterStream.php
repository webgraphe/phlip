<?php

namespace Webgraphe\Phlip\Stream;

use Webgraphe\Phlip\Stream;

/**
 * @method string content()
 */
class CharacterStream extends Stream
{
    public static function fromString(string $stream, string $name = null)
    {
        $stream = str_replace(["\r\n", "\r"], ["\n", "\n"], $stream);

        return new static($stream, strlen($stream), $name);
    }

    /**
     * @return string
     * @throws \Webgraphe\Phlip\Exception\StreamException
     */
    public function current(): string
    {
        return parent::current();
    }
}

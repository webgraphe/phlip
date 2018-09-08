<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Exception\StreamException;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Stream\LexemeStream;

class LexemeStreamTest extends TestCase
{
    /**
     * @throws StreamException
     */
    public function testEmpty()
    {
        $stream = LexemeStream::fromLexemes();
        $this->assertCount(0, $stream);
        $this->assertFalse($stream->valid());

        $this->expectException(StreamException::class);
        $this->assertNull($stream->current());
    }
}

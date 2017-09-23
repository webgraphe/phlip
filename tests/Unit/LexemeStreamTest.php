<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Stream\LexemeStream;

class LexemeStreamTest extends TestCase
{
    public function testEmpty()
    {
        $stream = LexemeStream::fromLexemes();
        $this->assertCount(0, $stream);
        $this->assertFalse($stream->valid());

        $this->expectException(\RuntimeException::class);
        $this->assertNull($stream->current());
    }
}

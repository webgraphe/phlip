<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Tests\TestCase;

class CodeAnchorTest extends TestCase
{
    public function testCodeAnchorNoOffset()
    {
        $code = 'this is a stream';
        $stream = CharacterStream::fromString($code);
        $stream->next()->next()->next()->next()->next();
        $anchor = new CodeAnchor($stream);
        $this->assertEquals($code, $anchor->getCode());
        $this->assertEquals($stream->key(), $anchor->getOffset());
        $this->assertNull($anchor->getSourceName());
    }

    public function testCodeAnchorWithOffset()
    {
        $code = 'this is a stream';
        $name = 'stream name';
        $stream = CharacterStream::fromString($code, $name);
        $anchor = new CodeAnchor($stream, 7);
        $this->assertEquals($code, $anchor->getCode());
        $this->assertEquals(7, $anchor->getOffset());
        $this->assertEquals($name, $anchor->getSourceName());
    }
}

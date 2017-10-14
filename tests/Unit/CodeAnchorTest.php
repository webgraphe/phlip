<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Tests\TestCase;

class CodeAnchorTest extends TestCase
{
    public function testCodeAnchorGetCode()
    {
        $stream = CharacterStream::fromString('abcdef');
        $anchor = new CodeAnchor($stream);
        $this->assertEquals('abcdef', $anchor->getCode());
    }

    public function testCodeAnchorGetSourceName()
    {
        $stream = CharacterStream::fromString('abcdef', 'source name');
        $anchor = new CodeAnchor($stream);
        $this->assertEquals('source name', $anchor->getSourceName());
    }

    public function testCodeAnchorNoOffset()
    {
        $stream = CharacterStream::fromString('abcdef');
        $anchor = new CodeAnchor($stream);
        $this->assertEquals(0, $stream->key());
        $this->assertEquals(0, $anchor->getOffset());

        $stream = CharacterStream::fromString('abcdef');
        $stream->next()->next()->next()->next()->next();
        $anchor = new CodeAnchor($stream);
        $this->assertEquals(5, $stream->key());
        $this->assertEquals(5, $anchor->getOffset());
    }

    public function testCodeAnchorWithOffset()
    {
        $stream = CharacterStream::fromString('abcdef');
        $anchor = new CodeAnchor($stream, 5);
        $this->assertEquals(5, $anchor->getOffset());
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Exception;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Exception\StreamException;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Tests\TestCase;

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

    public function testToStringException()
    {
        $this->assertEquals(
            'ERROR: fail',
            (string)LexemeStream::fromLexemes(NumberAtom::fromString('42'))->withLexemeStylizer(
                function () {
                    throw new Exception("fail");
                }
            )
        );
    }
}

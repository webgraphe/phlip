<?php

namespace Webgraphe\Phlip\Tests\Unit\Atom;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Atom\LiteralAtom;

class LiteralAtomTest extends TestCase
{
    public function testStringLiteral()
    {
        $literal = new LiteralAtom('string');
        $this->assertEquals('string', $literal->getValue());
        $this->assertFalse($literal->isNumber());
        $this->assertNull($literal->getNumberValue());
    }

    public function testIntegerLiteral()
    {
        $literal = new LiteralAtom('42');
        $this->assertEquals('42', $literal->getValue());
        $this->assertTrue($literal->isNumber());
        $this->assertEquals(42, $literal->getNumberValue());
    }

    public function testFloatLiteral()
    {
        $literal = new LiteralAtom('3.14');
        $this->assertEquals('3.14', $literal->getValue());
        $this->assertTrue($literal->isNumber());
        $this->assertEquals(3.14, $literal->getNumberValue());
    }
}

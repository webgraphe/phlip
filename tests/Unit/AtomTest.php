<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Atom\BooleanAtom;
use Webgraphe\Phlip\Atom\NullAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;

class AtomTest extends TestCase
{
    public function testStringAtom()
    {
        $string = new StringAtom('string');
        $this->assertEquals('string', $string->getValue());
    }

    public function testNumberAtom()
    {
        $integer = new NumberAtom('42');
        $this->assertEquals(42, $integer->getValue());
        $float = new NumberAtom('3.14');
        $this->assertEquals(3.14, $float->getValue());
    }

    public function testBooleanAtom()
    {
        $this->assertTrue(BooleanAtom::true()->getValue());
        $this->assertFalse(BooleanAtom::false()->getValue());
    }

    public function testNull()
    {
        $null = NullAtom::instance();
        $this->assertNull($null->getValue());
    }
}

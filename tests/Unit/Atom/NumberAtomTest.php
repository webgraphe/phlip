<?php

namespace Webgraphe\Phlip\Tests\Unit\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Tests\Unit\AtomTest;

class NumberAtomTest extends AtomTest
{
    public function testNumberAtom()
    {
        $integer = NumberAtom::fromString('42');
        $this->assertEquals(42, $integer->getValue());
        $float = NumberAtom::fromString('3.14');
        $this->assertEquals(3.14, $float->getValue());
    }

    protected function createAtom(CodeAnchor $anchor = null): Atom
    {
        return NumberAtom::fromString('42', $anchor);
    }

    public function testEvaluation()
    {
        $fortyTwo = NumberAtom::fromString('42');
        $this->assertEquals(42, $fortyTwo->evaluate(new Context));

        $pi = NumberAtom::fromString('3.14');
        $this->assertEquals(3.14, $pi->evaluate(new Context));
    }

    public function testStringConvertible()
    {
        $this->assertEquals('42', (string)NumberAtom::fromString('42'));
        $this->assertEquals('-3.14', (string)NumberAtom::fromString('-3.14'));
    }

    public function testIsNumber()
    {
        $this->assertTrue(NumberAtom::isNumber(42));
        $this->assertTrue(NumberAtom::isNumber(3.14));
        $this->assertTrue(NumberAtom::isNumber('-42'));
        $this->assertTrue(NumberAtom::isNumber('-4.15'));

        $this->assertFalse(NumberAtom::isNumber(true));
        $this->assertFalse(NumberAtom::isNumber(false));
        $this->assertFalse(NumberAtom::isNumber(null));
        $this->assertFalse(NumberAtom::isNumber([]));
    }
}

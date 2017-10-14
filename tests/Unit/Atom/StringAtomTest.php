<?php

namespace Webgraphe\Phlip\Tests\Unit\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Tests\Unit\AtomTest;

class StringAtomTest extends AtomTest
{
    public function testStringAtom()
    {
        $string = StringAtom::fromString('string');
        $this->assertEquals('string', $string->getValue());
    }

    protected function createAtom(CodeAnchor $anchor = null): Atom
    {
        return StringAtom::fromString('string', $anchor);
    }

    public function testEvaluation()
    {
        $thisIsAString = StringAtom::fromString('this is a string');
        $this->assertEquals('this is a string', $thisIsAString->evaluate(new Context));
    }

    public function testStringConvertible()
    {
        $this->assertEquals('"This string contains \\"quotes\\""', (string)StringAtom::fromString('This string contains "quotes"'));
    }
}

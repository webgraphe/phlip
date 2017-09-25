<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Tests\TestCase;

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

    public function testInvalidIdentifierAtom()
    {
        $this->expectException(AssertionException::class);
        IdentifierAtom::fromString(':(){}[]');
    }

    public function testEmptyKeyword()
    {
        $this->expectException(AssertionException::class);
        KeywordAtom::fromString('');
    }
}

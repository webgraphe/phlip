<?php

namespace Webgraphe\Phlip\Tests\Unit\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Tests\Unit\AtomTest;

class IdentifierAtomTest extends AtomTest
{
    public function testInvalidIdentifierAtom()
    {
        $this->expectException(AssertionException::class);
        IdentifierAtom::fromString(':(){}[]');
    }

    public function testEvaluation()
    {
        $context = new Context;
        $context->define('identifier', 'value');
        $this->assertEquals('value', IdentifierAtom::fromString('identifier')->evaluate($context));
    }

    public function testStringConvertible()
    {
        $this->assertEquals('identifier', (string)IdentifierAtom::fromString('identifier'));
    }

    protected function createAtom(CodeAnchor $anchor = null): Atom
    {
        return IdentifierAtom::fromString('identifier', $anchor);
    }
}

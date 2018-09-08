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
    /**
     * @throws AssertionException
     */
    public function testInvalidIdentifierAtom()
    {
        $this->expectException(AssertionException::class);
        IdentifierAtom::fromString(':(){}[]');
    }

    /**
     * @throws AssertionException
     * @throws \Webgraphe\Phlip\Exception\ContextException
     */
    public function testEvaluation()
    {
        $context = new Context;
        $context->define('identifier', 'value');
        $this->assertEquals('value', IdentifierAtom::fromString('identifier')->evaluate($context));
    }

    /**
     * @throws AssertionException
     */
    public function testStringConvertible()
    {
        $this->assertEquals('identifier', (string)IdentifierAtom::fromString('identifier'));
    }

    /**
     * @param CodeAnchor|null $anchor
     * @return Atom
     * @throws AssertionException
     */
    protected function createAtom(CodeAnchor $anchor = null): Atom
    {
        return IdentifierAtom::fromString('identifier', $anchor);
    }
}

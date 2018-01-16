<?php

namespace Webgraphe\Phlip\Tests\Unit\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Tests\Unit\AtomTest;

class KeywordAtomTest extends AtomTest
{
    /**
     * @throws AssertionException
     */
    public function testEmptyKeyword()
    {
        $this->expectException(AssertionException::class);
        KeywordAtom::fromString('');
    }

    /**
     * @throws AssertionException
     */
    public function testEvaluation()
    {
        $keyword = KeywordAtom::fromString('keyword');
        $this->assertEquals($keyword, $keyword->evaluate(new Context));
    }

    /**
     * @throws AssertionException
     */
    public function testStringConvertible()
    {
        $this->assertEquals('#keyword', (string)KeywordAtom::fromString('keyword'));
    }

    /**
     * @param CodeAnchor|null $anchor
     * @return Atom
     * @throws AssertionException
     */
    protected function createAtom(CodeAnchor $anchor = null): Atom
    {
        return KeywordAtom::fromString('#keyword', $anchor);
    }
}

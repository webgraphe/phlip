<?php

namespace Webgraphe\Phlip\Tests\Unit\FormCollection;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\DottedPair;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Tests\TestCase;

class DottedPairTest extends TestCase
{
    /**
     * @throws AssertionException
     */
    public function testUnexpectedProperList()
    {
        $this->expectException(AssertionException::class);
        DottedPair::fromForms(IdentifierAtom::fromString('+'), new FormList());
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Unit\FormCollection;

use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Tests\TestCase;

class MapTest extends TestCase
{
    public function testOddNumberOfForms()
    {
        $this->expectException(AssertionException::class);
        $this->expectExceptionMessage("Expected a proper list of 2 forms; got 3");

        new Map(
            new ProperList(
                StringAtom::fromString("first"),
                StringAtom::fromString("second"),
                StringAtom::fromString("third")
            )
        );
    }
}

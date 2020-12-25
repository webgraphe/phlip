<?php

namespace Webgraphe\Phlip\Tests\Unit\Atom;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Tests\TestCase;

class IdentifierAtomTest extends TestCase
{
    public function testInvalidIdentifier()
    {
        $this->expectException(AssertionException::class);
        $this->expectExceptionMessage("Invalid identifier");

        IdentifierAtom::fromString("{}");
    }

    public function testInvalidIdentifierStartingWithADigit()
    {
        $this->expectException(AssertionException::class);
        $this->expectExceptionMessage("Invalid identifier");

        IdentifierAtom::fromString("9nine");
    }
}

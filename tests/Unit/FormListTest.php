<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Tests\TestCase;

class FormListTest extends TestCase
{
    public function testEmptyList()
    {
        $list = new FormList;
        $this->expectException(AssertionException::class);
        $list->assertHead();
    }

    public function testNotCallable()
    {
        $list = new FormList(IdentifierAtom::fromString('not-callable'));
        $context = new Context;
        $context->define('not-callable', "This is a callable");

        $this->expectException(AssertionException::class);
        $list->evaluate($context);
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\ExpressionList;

class ExpressionListTest extends TestCase
{
    public function testEmptyList()
    {
        $list = new ExpressionList;
        $this->expectException(\RuntimeException::class);
        $list->assertHeadExpression();
    }

    public function testNotCallable()
    {
        $list = new ExpressionList(new IdentifierAtom('not-callable'));
        $context = new Context;
        $context->define('not-callable', "This is a callable");

        $this->expectException(\RuntimeException::class);
        $list->evaluate($context);
    }
}

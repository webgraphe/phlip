<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;

class ExpressionListTest extends TestCase
{
    public function testEmptyList()
    {
        $list = new ExpressionList;
        $this->expectException(AssertionException::class);
        $list->assertHeadExpression();
    }

    public function testNotCallable()
    {
        $list = new ExpressionList(new IdentifierAtom('not-callable'));
        $context = new Context;
        $context->define('not-callable', "This is a callable");

        $this->expectException(AssertionException::class);
        $list->evaluate($context);
    }
}

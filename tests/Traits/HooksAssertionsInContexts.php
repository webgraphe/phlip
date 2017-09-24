<?php

namespace Webgraphe\Phlip\Tests\Traits;

use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Tests\CallablePrimaryFunctionOperation;

/**
 * @method void assertTrue($condition, $message = '')
 * @method void assertEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
 * @method void expectException($exception)
 * @see \PHPUnit\Framework\Assert::assertTrue()
 * @see \PHPUnit\Framework\Assert::assertEquals()
 * @see \PHPUnit\Framework\TestCase::expectException()
 */
trait HooksAssertionsInContexts
{
    protected function contextWithAsserts(ContextContract $context = null): ContextContract
    {
        $context = $context ?? new PhlipyContext;
        $context->define('ContextException', ContextException::class);
        $context->define('EvaluationException', EvaluationException::class);
        $context->define(
            'assert',
            new CallablePrimaryFunctionOperation(
                function (ContextContract $context, ExpressionList $expressions) {
                    $head = $expressions->assertHeadExpression();
                    $this->assertTrue((bool)$head->evaluate($context), "Expected $head to be true");
                }
            )
        );
        $context->define(
            'assert-equals',
            new CallablePrimaryFunctionOperation(
                function (ContextContract $context, ExpressionList $expressions) {
                    $head = $expressions->assertHeadExpression()->evaluate($context);
                    $toeExpression = $expressions->getTailExpressions()->assertHeadExpression();
                    $toe = $toeExpression->evaluate($context);
                    if ($head instanceof ExpressionContract && $toe instanceof ExpressionContract) {
                        $this->assertTrue($head->equals($toe), "Expected $head; got $toe");
                    } else {
                        $headType = is_object($head) ? get_class($head) : gettype($head);
                        $toeType = is_object($toe) ? get_class($toe) : gettype($toe);
                        $this->assertEquals($head, $toe, "Expected $headType out of $toeExpression; got $toeType");
                    }
                }
            )
        );
        $context->define(
            'assert-exception',
            new CallablePrimaryFunctionOperation(
                function (ContextContract $context, ExpressionList $expressions) {
                    /** @var self $test */
                    $name = $expressions->assertHeadExpression()->evaluate($context);
                    $this->expectException($name);
                    $expressions->getTailExpressions()->assertHeadExpression()->evaluate($context);
                }
            )
        );

        return $context;
    }
}

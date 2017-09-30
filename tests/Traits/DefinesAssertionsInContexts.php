<?php

namespace Webgraphe\Phlip\Tests\Traits;

use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\Collection\ProperList;
use Webgraphe\Phlip\Tests\CallablePrimaryOperationOperation;

/**
 * @method void assertTrue($condition, $message = '')
 * @method void assertEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
 * @method void expectException($exception)
 * @see \PHPUnit\Framework\Assert::assertTrue()
 * @see \PHPUnit\Framework\Assert::assertEquals()
 * @see \PHPUnit\Framework\TestCase::expectException()
 */
trait DefinesAssertionsInContexts
{
    protected function contextWithAsserts(ContextContract $context = null): ContextContract
    {
        $context = $context ?? new PhlipyContext;
        $context->define('ContextException', ContextException::class);
        $context->define('EvaluationException', EvaluationException::class);
        $context->define(
            'assert-true',
            new CallablePrimaryOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    $head = $expressions->assertHead();
                    $this->assertTrue((bool)$head->evaluate($context), "Expected $head to be true");
                }
            )
        );
        $context->define(
            'assert-false',
            new CallablePrimaryOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    $head = $expressions->assertHead();
                    $this->assertFalse((bool)$head->evaluate($context), "Expected $head to be false");
                }
            )
        );
        $context->define(
            'assert-equals',
            new CallablePrimaryOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    $head = $expressions->assertHead()->evaluate($context);
                    $toeExpression = $expressions->getTail()->assertHead();
                    $toe = $toeExpression->evaluate($context);
                    try {
                        if ($head instanceof FormContract && $toe instanceof FormContract) {
                            $this->assertTrue($head->equals($toe),
                                "Expected $head out of got $toeExpression; got $toe");
                        } else {
                            $headType = is_object($head) ? get_class($head) : gettype($head);
                            $toeType = is_object($toe) ? get_class($toe) : gettype($toe);
                            $this->assertEquals($head, $toe, "Expected $headType out of $toeExpression; got $toeType");
                        }
                    } catch (\Throwable $t) {
                        throw $t;
                    }
                }
            )
        );
        $context->define(
            'assert-not-equals',
            new CallablePrimaryOperationOperation(
                function (ContextContract $context, ProperList $expressions)
                {
                    $head = $expressions->assertHead()->evaluate($context);
                    $toeExpression = $expressions->getTail()->assertHead();
                    $toe = $toeExpression->evaluate($context);
                    if ($head instanceof FormContract && $toe instanceof FormContract) {
                        $this->assertTrue(!$head->equals($toe), "Didn't expect $head out of $toeExpression");
                    } else {
                        $headType = is_object($head) ? get_class($head) : gettype($head);
                        $toeType = is_object($toe) ? get_class($toe) : gettype($toe);
                        $this->assertNotEquals($head, $toe, "Expected $headType out of $toeExpression; got $toeType");
                    }
                }
            )
        );
        $context->define(
            'assert-exception',
            new CallablePrimaryOperationOperation(
                function (ContextContract $context, ProperList $expressions)
                {
                    /** @var self $test */
                    $name = $expressions->assertHead()->evaluate($context);
                    $this->expectException($name);
                    $expressions->getTail()->assertHead()->evaluate($context);
                }
            )
        );

        return $context;
    }
}

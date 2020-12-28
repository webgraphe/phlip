<?php

namespace Webgraphe\Phlip\Tests\Traits;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\PhpClassInteroperableContext;
use Webgraphe\Phlip\Tests\CallableManualOperationOperation;
use Webgraphe\Phlip\Tests\Dummy;

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
    private function stringize($anything): string
    {
        if (is_scalar($anything) || (is_object($anything) && method_exists($anything, '__toString'))) {
            return (string)$anything;
        }

        return is_object($anything) ? get_class($anything) : gettype($anything);
    }

    /**
     * @return ContextContract
     * @throws ContextException
     */
    protected function contextWithAssertions(): ContextContract
    {
        $context = Phlipy::interoperable()->withStringFunctions()->withMathFunctions()->getContext();
        $context->define(
            'assert-true',
            new CallableManualOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    $head = $expressions->assertHead();
                    $this->assertTrue((bool)$context->execute($head), "Expected $head to be true");
                }
            )
        );
        $context->define(
            'assert-false',
            new CallableManualOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    $head = $expressions->assertHead();
                    $this->assertFalse((bool)$context->execute($head), "Expected $head to be false");
                }
            )
        );
        $context->define(
            'assert-equals',
            new CallableManualOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    $head = $context->execute($expressions->assertHead());
                    $toeExpression = $expressions->assertTailHead();
                    $toe = $context->execute($toeExpression);
                    if ($head instanceof FormContract && $toe instanceof FormContract) {
                        $this->assertTrue(
                            $head->equals($toe),
                            "Expected $head out of $toeExpression; got $toe"
                        );
                    } else {
                        $headString = $this->stringize($head);
                        $toeString = $this->stringize($toe);
                        $this->assertEquals($head, $toe, "Expected $headString out of $toeExpression; got $toeString");
                    }
                }
            )
        );
        $context->define(
            'assert-not-equals',
            new CallableManualOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    $head = $context->execute($expressions->assertHead());
                    $toeExpression = $expressions->assertTailHead();
                    $toe = $context->execute($toeExpression);
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

        $context->define('AssertionException', AssertionException::class);
        $context->define('ContextException', ContextException::class);
        $context->define('ProgramException', ProgramException::class);
        $context->define(
            'assert-exception',
            new CallableManualOperationOperation(
                function (ContextContract $context, ProperList $expressions) {
                    /** @var self $test */
                    $name = $context->execute($expressions->assertHead());
                    $this->expectException($name);
                    if ($message = $expressions->getTail()->getTail()->getHead()) {
                        $this->expectExceptionMessage($context->execute($message));
                    }
                    $context->execute($expressions->assertTailHead());
                }
            )
        );
        if ($context instanceof PhpClassInteroperableContext) {
            $context->enableClass(Dummy::class);
            $context->enableClass('Undefined');
        }

        return $context;
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Traits;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\PhpClassInteroperableScope;
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
trait DefinesAssertionsInScopes
{
    private function stringize($anything): string
    {
        if (is_scalar($anything) || (is_object($anything) && method_exists($anything, '__toString'))) {
            return (string)$anything;
        }

        return is_object($anything) ? get_class($anything) : gettype($anything);
    }

    /**
     * @return ScopeContract
     */
    protected function scopeWithAssertions(): ScopeContract
    {
        $scope = Phlipy::interoperable()->withStringFunctions()->withMathFunctions()->getScope();
        $scope->define(
            'assert-true',
            new CallableManualOperationOperation(
                function (ScopeContract $scope, FormList $expressions) {
                    $head = $expressions->assertHead();
                    $this->assertTrue((bool)$scope->execute($head), "Expected $head to be true");
                }
            )
        );
        $scope->define(
            'assert-false',
            new CallableManualOperationOperation(
                function (ScopeContract $scope, FormList $expressions) {
                    $head = $expressions->assertHead();
                    $this->assertFalse((bool)$scope->execute($head), "Expected $head to be false");
                }
            )
        );
        $scope->define(
            'assert-equals',
            new CallableManualOperationOperation(
                function (ScopeContract $scope, FormList $expressions) {
                    $head = $scope->execute($expressions->assertHead());
                    $toeExpression = $expressions->assertTailHead();
                    $toe = $scope->execute($toeExpression);
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
        $scope->define(
            'assert-not-equals',
            new CallableManualOperationOperation(
                function (ScopeContract $scope, FormList $expressions) {
                    $head = $scope->execute($expressions->assertHead());
                    $toeExpression = $expressions->assertTailHead();
                    $toe = $scope->execute($toeExpression);
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

        $scope->define('AssertionException', AssertionException::class);
        $scope->define('ScopeException', ScopeException::class);
        $scope->define('ProgramException', ProgramException::class);
        $scope->define(
            'assert-exception',
            new CallableManualOperationOperation(
                function (ScopeContract $scope, FormList $expressions) {
                    /** @var self $test */
                    $name = $scope->execute($expressions->assertHead());
                    $this->expectException($name);
                    if ($message = $expressions->getTail()->getTail()->getHead()) {
                        $this->expectExceptionMessage($scope->execute($message));
                    }
                    $scope->execute($expressions->assertTailHead());
                }
            )
        );
        if ($scope instanceof PhpClassInteroperableScope) {
            $scope->enableClass(Dummy::class);
            $scope->enableClass('Undefined');
        }

        return $scope;
    }
}

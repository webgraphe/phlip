<?php

namespace Tests\Webgraphe\Phlip;

use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct\CallablePrimaryFunctionOperation;
use Webgraphe\Phlip\Program;

class PhlipScriptTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var string */
    private $file;

    public function __construct(string $file)
    {
        parent::__construct('testScript');
        $this->file = $file;
    }

    public function testScript()
    {
        $context = $this->contextWithAsserts();
        Program::parseFile($this->file)->execute($context);
    }

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return $this->file;
    }

    protected function contextWithAsserts(ContextContract $context = null): ContextContract
    {
        $context = $context ?? new PhlipyContext;
        $context->define('AssertionException', AssertionException::class);
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
                        $this->assertEquals($head, $toe, "Expected $head out of $toeExpression; got $toe");
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

<?php

namespace Webgraphe\Phlip\Tests\Integration;

use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct\CallablePrimaryFunctionOperation;
use Webgraphe\Phlip\Program;

class ScriptsTest extends TestCase
{
    /**
     * @dataProvider scriptFiles
     * @param string $file
     */
    public function testScripts($file)
    {
        $context = $this->contextWithAsserts();
        Program::parseFile($file)->execute($context);
    }

    /**
     * Not your typical data provider. Loads .phlip scripts and evaluate (test) statements to retrieve their expression
     * lists and return them.
     *
     * NOTE: This method executes code that won't be tracked by PHPUnit's code coverage as is any code executed within
     * a data provider. This means any ContextContract related code or operation initialization calls won't be tracked.
     *
     * @return array
     */
    public function scriptFiles()
    {
        $files = $this->globRecursive(
            $this->relativeProjectPath('tests/Integration/Scripts'),
            function (\DirectoryIterator $iterator) {
                return $iterator->isFile() && preg_match('/Test\\.phlip$/', $iterator->getFilename());
            }
        );
        return array_map(
            function (string $file) {
                return ['file' => $file];
            },
            array_combine($files, $files)
        );
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

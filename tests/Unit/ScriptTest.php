<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct\CallablePrimaryFunctionOperation;
use Webgraphe\Phlip\Program;

class ScriptTest extends TestCase
{
    /**
     * @dataProvider scripts
     * @param ContextContract $context
     * @param ExpressionContract[] $expressions
     */
    public function testScripts(ContextContract $context, ExpressionContract ...$expressions)
    {
        $context->define('__testCase', $this);
        foreach ($expressions as $expression) {
            $expression->evaluate($context);
        }
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
    public function scripts()
    {
        $tests = [];
        $failingScripts = [];

        $filter = function (\DirectoryIterator $iterator) {
            return $iterator->isFile() && 'phlip' === strtolower($iterator->getExtension());
        };
        foreach ($this->globRecursive($this->relativeProjectPath('tests/scripts'), $filter) as $file) {
            $context = new PhlipyContext;
            $this->contextWithTest($context, $tests);
            try {
                Program::parseFile($file)->execute($context);
            } catch (\Throwable $t) {
                $failingScripts[$file] = $t;
            }
        }

        if ($failingScripts) {
            $this->fail(
                "Script failures:" . PHP_EOL
                . implode(
                    PHP_EOL,
                    array_map(
                        function ($file, \Throwable $t) {
                            $type = get_class($t);
                            return "  $file: ($type) {$t->getMessage()}";
                        },
                        array_keys($failingScripts),
                        array_values($failingScripts)
                    )
                )
            );
        }

        return $tests;
    }

    protected function contextWithTest(ContextContract $context = null, array &$tests = [])
    {
        $context = $context ?? new PhlipyContext;
        $context->define(
            'test',
            new CallablePrimaryFunctionOperation(
                function (ContextContract $context, ExpressionList $expressions) use (&$tests) {
                    $name = $expressions->assertHeadExpression()->evaluate($context);

                    return $tests[$name] = array_merge(
                        [$this->contextWithAsserts()],
                        $expressions->getTailExpressions()->all()
                    );
                }
            )
        );

        return $context;
    }

    protected function contextWithAsserts(ContextContract $context = null): ContextContract
    {
        $context = $context ?? new PhlipyContext;
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
                    $toe = $expressions->getTailExpressions()->assertHeadExpression()->evaluate($context);
                    if ($head instanceof ExpressionContract && $toe instanceof ExpressionContract) {
                        $this->assertTrue($head->equals($toe), "Expected $head; got $toe");
                    } else {
                        $this->assertEquals($head, $toe);
                    }
                }
            )
        );
        $context->define(
            'assert-exception',
            new CallablePrimaryFunctionOperation(
                function (ContextContract $context, ExpressionList $expressions) {
                    /** @var self $test */
                    $test = $context->get('__testCase');
                    $name = $expressions->assertHeadExpression()->evaluate($context);
                    $test->expectException($name);
                    $expressions->getTailExpressions()->assertHeadExpression()->evaluate($context);
                }
            )
        );

        return $context;
    }
}

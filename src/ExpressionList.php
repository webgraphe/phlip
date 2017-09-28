<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class ExpressionList implements ExpressionContract, \Countable
{
    use AssertsStaticType;

    /** @var ExpressionContract[] */
    private $expressions;

    public function __construct(ExpressionContract ...$expressions)
    {
        $this->expressions = $expressions;
    }

    public static function asList(ExpressionContract $expression): ExpressionList
    {
        return $expression instanceof ExpressionList
            ? $expression
            : new ExpressionList($expression);
    }

    public function getHeadExpression(): ?ExpressionContract
    {
        return $this->expressions[0] ?? null;
    }

    /**
     * @return ExpressionContract
     * @throws AssertionException
     */
    public function assertHeadExpression(): ExpressionContract
    {
        $head = $this->getHeadExpression();
        if (!$head) {
            throw new AssertionException("List is empty");
        }

        return $head;
    }

    public function getTailExpressions(): ExpressionList
    {
        return new ExpressionList(...array_slice($this->expressions, 1, null, false));
    }

    /**
     * @return ExpressionContract[]
     */
    public function all(): array
    {
        return $this->expressions;
    }

    public function __toString(): string
    {
        return '('
            . implode(
                ' ',
                array_map(
                    function (ExpressionContract $expression) {
                        return (string)$expression;
                    },
                    $this->expressions
                )
            )
            . ')';
    }

    /**
     * @param ContextContract $context
     * @return mixed
     * @throws EvaluationException
     */
    public function evaluate(ContextContract $context)
    {
        if (!$this->getHeadExpression()) {
            return null;
        }

        $callable = self::assertCallable($context, $this->assertHeadExpression());
        $arguments = $callable instanceof PrimaryOperationContract
            ? array_merge([$context], $this->getTailExpressions()->all())
            : array_map(
                function (ExpressionContract $expression) use ($context) {
                    return $expression->evaluate($context);
                },
                $this->getTailExpressions()->expressions
            );

        try {
            return call_user_func($callable, ...$arguments);
        } catch (AssertionException $assertion) {
            throw EvaluationException::fromExpression($this, 'Evaluation failed', 0, $assertion);
        }
    }

    /**
     * @param ContextContract $context
     * @param ExpressionContract $expression
     * @return callable
     * @throws AssertionException
     */
    protected static function assertCallable(ContextContract $context, ExpressionContract $expression): callable
    {
        if (!is_callable($thing = $expression->evaluate($context))) {
            $type = is_object($thing) ? get_class($thing) : gettype($thing);
            throw new AssertionException("Not a callable; got '$type' from $expression");
        }

        return $thing;
    }

    public function count(): int
    {
        return count($this->expressions);
    }

    public function equals(ExpressionContract $against): bool
    {
        return $against instanceof static
            && count($this->expressions) === count($against->expressions)
            && count($this->expressions) === count(
                array_filter(
                    array_map(
                        function (ExpressionContract $left, $right) {
                            return $left->equals($right);
                        },
                        $this->expressions,
                        $against->expressions
                    )
                )
            );
    }
}

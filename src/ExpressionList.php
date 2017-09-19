<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\LanguageConstructContract;
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
        return $expression instanceof ExpressionList ? $expression : new ExpressionList($expression);
    }

    public static function fromArray(array $array)
    {
        $list = [];
        foreach ($array as $element) {
            switch (true) {
                case $element instanceof ExpressionContract:
                    $list[] = $element;
                    break;
                case is_bool($element):
                    $list[] = new IdentifierAtom($element ? 'true' : 'false');
            }
        }
    }

    public function getHeadExpression(): ?ExpressionContract
    {
        return $this->expressions[0] ?? null;
    }

    /**
     * @return ExpressionContract
     * @throws \RuntimeException
     */
    public function assertHeadExpression(): ExpressionContract
    {
        $head = $this->getHeadExpression();
        if (!$head) {
            throw new \RuntimeException("Empty");
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
     */
    public function evaluate(ContextContract $context)
    {
        $callable = self::assertCallable($this->getHeadExpression()->evaluate($context));
        $arguments = $callable instanceof LanguageConstructContract
            ? array_merge([$context], $this->getTailExpressions()->all())
            : array_map(
                function (ExpressionContract $expression) use ($context) {
                    return $expression->evaluate($context);
                },
                $this->getTailExpressions()->expressions
            );

        return call_user_func($callable, ...$arguments);
    }

    /**
     * @param mixed $thing
     * @return callable
     */
    protected static function assertCallable($thing): callable
    {
        if (!is_callable($thing)) {
            $type = is_object($thing) ? get_class($thing) : gettype($thing);
            throw new \RuntimeException("Assertion failed; expected a callable, got '$type'");
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
                array_map(
                    function (ExpressionContract $left, $right) {
                        return $left->equals($right);
                    },
                    $this->expressions,
                    $against->expressions
                )
            );
    }
}

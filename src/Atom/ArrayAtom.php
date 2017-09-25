<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;

class ArrayAtom extends Atom implements \Countable
{
    public function __construct(ExpressionContract ...$elements)
    {
        parent::__construct($elements);
    }

    /**
     * @return ExpressionContract[]
     */
    public function getValue(): array
    {
        return parent::getValue();
    }

    /**
     * @param ContextContract $context
     * @return array
     */
    public function evaluate(ContextContract $context)
    {
        return array_map(
            function (ExpressionContract $expression) use ($context) {
                return $expression->evaluate($context);
            },
            $this->getValue()
        );
    }

    public function __toString(): string
    {
        return '['
            . implode(
                ' ',
                array_map(
                    function ($element) {
                        return (string)$element;
                    },
                    $this->getValue()
                )
            )
            . ']';
    }

    public function equals(ExpressionContract $against): bool
    {
        $expressionCount = count($this);
        if (!($against instanceof static) || count($against) !== $expressionCount) {
            return false;
        }

        return $expressionCount === count(
            array_filter(
                array_map(
                    function(ExpressionContract $left, ExpressionContract $right) {
                        return $left->equals($right);
                    },
                    $this->getValue(),
                    $against->getValue()
                )
            )
        );
    }

    public function count()
    {
        return count($this->getValue());
    }
}

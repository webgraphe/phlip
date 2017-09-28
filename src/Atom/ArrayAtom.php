<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;

class ArrayAtom extends Atom implements \Countable
{
    public function __construct(FormContract ...$elements)
    {
        parent::__construct($elements);
    }

    /**
     * @return FormContract[]
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
            function (FormContract $expression) use ($context) {
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

    public function equals(FormContract $against): bool
    {
        $expressionCount = count($this);
        if (!($against instanceof static) || count($against) !== $expressionCount) {
            return false;
        }

        return $expressionCount === count(
            array_filter(
                array_map(
                    function(FormContract $left, FormContract $right) {
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

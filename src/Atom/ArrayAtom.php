<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;

class ArrayAtom extends Atom
{
    public function __construct(ExpressionContract ...$elements)
    {
        parent::__construct($elements);
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
}

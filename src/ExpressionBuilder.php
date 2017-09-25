<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\ArrayAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\AssertionException;

class ExpressionBuilder
{
    /**
     * Normalizes something into an expression.
     * - Attempts to recreate expressions depending on the type.
     * - If it's already an expression, returns the expression itself.
     *
     * @param mixed $thing
     * @return ExpressionContract
     * @throws AssertionException If the type of the input data could not be handled.
     */
    public function asExpression($thing): ExpressionContract
    {
        static $true, $false, $null;

        switch (true) {
            case $thing instanceof ExpressionContract:
                return $thing;
            case null === $thing:
                return $null ?? ($null = new ExpressionList);
            case true === $thing:
                return $true ?? ($true = KeywordAtom::fromString('true'));
            case false === $thing:
                return $false ?? ($false = new ExpressionList);
            case is_string($thing):
                return new StringAtom($thing);
            case is_numeric($thing):
                return new NumberAtom($thing);
            case is_array($thing):
                return new ArrayAtom(
                    ...array_map(
                        function ($element) {
                            return $this->asExpression($element);
                        },
                        $thing
                    )
                );
            default:
                $type = is_object($thing) ? get_class($thing) : gettype($thing);
                throw new AssertionException("Unhandled '$type'");
        }
    }
}

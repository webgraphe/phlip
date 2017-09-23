<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\BooleanAtom;
use Webgraphe\Phlip\Atom\NullAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation;

class ExpressionBuilder
{
    /**
     * @param mixed $thing
     * @return ExpressionContract
     */
    public function asExpression($thing): ExpressionContract
    {
        switch (true) {
            case $thing instanceof ExpressionContract:
                return $thing;
            case null === $thing:
                return NullAtom::instance();
            case is_bool($thing):
                return $thing ? BooleanAtom::true() : BooleanAtom::false();
            case is_string($thing):
                return new StringAtom($thing);
            case is_numeric($thing):
                return new NumberAtom((string)$thing);
            case is_array($thing):
                return new ExpressionList(
                    ...array_map(
                        function ($element) {
                            return $this->asExpression($element);
                        },
                        $thing
                    )
                );
                break;
            default:
                $type = is_object($thing) ? get_class($thing) : gettype($thing);
                throw new \RuntimeException("Unhandled '$type'");
        }
    }
}

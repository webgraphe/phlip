<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\ArrayAtom;
use Webgraphe\Phlip\Atom\BooleanAtom;
use Webgraphe\Phlip\Atom\NullAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\AssertionException;

class ExpressionBuilder
{
    /**
     * @param mixed $thing
     * @return ExpressionContract
     * @throws AssertionException
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
                return new NumberAtom($thing);
            case is_array($thing):
                return new ArrayAtom(...$thing);
            default:
                $type = is_object($thing) ? get_class($thing) : gettype($thing);
                throw new AssertionException("Unhandled '$type'");
        }
    }
}

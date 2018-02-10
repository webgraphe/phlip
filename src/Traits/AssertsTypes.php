<?php

namespace Webgraphe\Phlip\Traits;

use Webgraphe\Phlip\Exception\AssertionException;

trait AssertsTypes
{
    /**
     * @param mixed $expected
     * @param mixed $thing
     * @return mixed
     * @throws AssertionException
     */
    public static function assertType($expected, $thing)
    {
        if (null !== $thing && !is_a($thing, $expected)) {
            $actual = is_object($thing) ? get_class($thing) : gettype($thing);
            if (is_scalar($thing) || is_object($thing) && method_exists($thing, '__toString')) {
                $actual .= " $thing";
            }

            throw new AssertionException("Expected $expected, got $actual");
        }

        return $thing;
    }
}

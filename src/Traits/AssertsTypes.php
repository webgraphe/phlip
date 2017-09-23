<?php

namespace Webgraphe\Phlip\Traits;

use Webgraphe\Phlip\Exception\AssertionException;

trait AssertsTypes
{
    public static function assertType($expected, $thing)
    {
        if (null !== $thing && !is_a($thing, $expected)) {
            $actual = is_object($thing) ? get_class($thing) : gettype($thing);

            throw new AssertionException("Expected '$expected', got $actual");
        }

        return $thing;
    }
}

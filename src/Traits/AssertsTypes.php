<?php

namespace Webgraphe\Phlip\Traits;

trait AssertsTypes
{
    public static function assertType($expected, $thing)
    {
        if (null !== $thing && !is_a($thing, $expected)) {
            $actual = is_object($thing) ? get_class($thing) : gettype($thing);

            throw new \RuntimeException("Assertion failed; expected '$expected', got $actual");
        }

        return $thing;
    }

    public static function assertNotType($expected, $thing)
    {
        if (null === $thing && is_a($thing, $expected)) {
            throw new \RuntimeException("Assertion failed; was not expecting a '$expected'");
        }

        return $thing;
    }
}

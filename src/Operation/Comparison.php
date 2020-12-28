<?php

namespace Webgraphe\Phlip\Operation;

use stdClass;
use Webgraphe\Phlip\Exception\AssertionException;

abstract class Comparison extends AutomaticOperation
{
    /**
     * @param bool|int|float|string|stdClass|array $thing
     * @return mixed
     * @throws AssertionException
     */
    protected static function assertNativeValue($thing)
    {
        if (is_resource($thing) || is_object($thing) && !($thing instanceof stdClass)) {
            $type = is_object($thing) ? get_class($thing) : gettype($thing);

            throw new AssertionException("Not a native value; got '$type'");
        }

        return $thing;
    }
}

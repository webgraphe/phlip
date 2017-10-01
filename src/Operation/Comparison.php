<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Operation;

abstract class Comparison extends Operation implements StandardOperationContract
{
    /**
     * @param mixed $thing
     * @return number
     * @throws AssertionException
     */
    protected static function assertNativeValue($thing)
    {
        if (is_resource($thing) || is_object($thing) && !($thing instanceof \stdClass)) {
            $type = is_object($thing) ? get_class($thing) : gettype($thing);
            throw new AssertionException("Not a native value; got '$type'");
        }

        return $thing;
    }
}
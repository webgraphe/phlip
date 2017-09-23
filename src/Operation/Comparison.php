<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\Operation;

abstract class Comparison extends Operation implements FunctionContract
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
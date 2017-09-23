<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

abstract class Comparison extends Operation implements FunctionContract
{
    /**
     * @param mixed $thing
     * @return number
     */
    protected static function assertValue($thing)
    {
        if (is_resource($thing) || is_object($thing) && !($thing instanceof \stdClass)) {
            throw new \RuntimeException('Not a value');
        }

        return $thing;
    }
}
<?php

namespace Webgraphe\Phlip\Traits;

use Webgraphe\Phlip\Exception\AssertionException;

trait AssertsObjects
{
    /**
     * @param mixed $thing
     * @return object
     * @throws AssertionException
     */
    public static function assertObject($thing): object
    {
        if (!is_object($thing)) {
            throw new AssertionException("Not an object");
        }

        return $thing;
    }
}

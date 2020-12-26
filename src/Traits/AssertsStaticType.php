<?php

namespace Webgraphe\Phlip\Traits;

use Webgraphe\Phlip\Exception\AssertionException;

trait AssertsStaticType
{
    use AssertsTypes;

    /**
     * @param mixed $thing
     * @return static
     * @throws AssertionException
     */
    public static function assertStaticType($thing): self
    {
        return static::assertType(static::class, $thing);
    }
}

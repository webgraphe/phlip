<?php

namespace Webgraphe\Phlip\Traits;

trait AssertsStaticType
{
    use AssertsTypes;

    /**
     * @param mixed $thing
     * @return static
     */
    public static function assertStaticType($thing)
    {
        return static::assertType(static::class, $thing);
    }
}

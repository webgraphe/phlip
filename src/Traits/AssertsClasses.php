<?php

namespace Webgraphe\Phlip\Traits;

use Webgraphe\Phlip\Contracts\PhpClassInteroperableContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;

trait AssertsClasses
{
    /**
     * @param string $identifier
     * @return object|string
     * @throws AssertionException
     */
    public static function assertClassExists(string $identifier)
    {
        if (!class_exists($identifier)) {
            throw new AssertionException("Class '{$identifier}' not found");
        }

        return $identifier;
    }

    /**
     * @param PhpClassInteroperableContract $context
     * @param string|object $classOrInstance
     * @return object|string
     * @throws AssertionException
     * @throws ContextException
     */
    public static function assertClassEnabled(PhpClassInteroperableContract $context, $classOrInstance)
    {
        $class = is_object($classOrInstance) ? get_class($classOrInstance) : $classOrInstance;

        if (!$context->isClassEnabled($class)) {
            throw new ContextException("PHP Class '{$class}' interoperability is not enabled");
        }

        return static::assertClassExists($class);
    }
}

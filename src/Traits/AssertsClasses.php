<?php

namespace Webgraphe\Phlip\Traits;

use Webgraphe\Phlip\Contracts\PhpClassInteroperableContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;

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
     * @param PhpClassInteroperableContract $scope
     * @param string|object $classOrInstance
     * @return object|string
     * @throws AssertionException
     * @throws ScopeException
     */
    public static function assertClassEnabled(PhpClassInteroperableContract $scope, $classOrInstance)
    {
        $class = is_object($classOrInstance) ? get_class($classOrInstance) : $classOrInstance;

        if (!$scope->isClassEnabled($class)) {
            throw new ScopeException("PHP Class '{$class}' interoperability is not enabled");
        }

        return static::assertClassExists($class);
    }
}

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
    private function assertClassExists(string $identifier)
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
    private function assertClassEnabled(PhpClassInteroperableContract $context, $classOrInstance)
    {
        $identifier = is_object($classOrInstance)
            ? get_class($classOrInstance)
            : (string)$classOrInstance;

        if (!$context->isClassEnabled($identifier)) {
            throw new ContextException("PHP Class '{$identifier}' is not enabled");
        }

        return $this->assertClassExists($identifier);
    }
}

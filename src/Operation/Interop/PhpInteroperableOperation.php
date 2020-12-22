<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\PhpClassInteroperableContract;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Operation\PrimaryOperation;

abstract class PhpInteroperableOperation extends PrimaryOperation
{
    /**
     * @param ContextContract $context
     * @param string $class
     * @return ContextContract|PhpClassInteroperableContract
     * @throws ContextException
     */
    protected function assertPhpInteroperableContext(ContextContract $context, string $class): ContextContract
    {
        if (!is_subclass_of($context, PhpClassInteroperableContract::class)) {
            throw new ContextException("Class '{$class}' requires an PHP interoperable context");
        }

        return $context;
    }
}

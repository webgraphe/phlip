<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\PhpClassInteroperableContract;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Operation\ManualOperation;

abstract class PhpInteroperableOperation extends ManualOperation
{
    /**
     * @param ContextContract $context
     * @param string $class
     * @return ContextContract|PhpClassInteroperableContract
     * @throws ContextException
     */
    protected function assertPhpInteroperableContext(ContextContract $context, string $class): ContextContract
    {
        if ($context instanceof PhpClassInteroperableContract) {
            return $context;
        }

        throw new ContextException("Class '{$class}' requires an PHP interoperable context");
    }
}

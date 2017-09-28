<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * Not an absolute requirement, but guarantees an operation will be invokable in a context.
 * @see PrimaryOperationContract A mutually exclusive contract for operations that require non evaluated forms.
 */
interface StandardOperationContract extends OperationContract
{
    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments);
}

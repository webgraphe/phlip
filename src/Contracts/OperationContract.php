<?php

namespace Webgraphe\Phlip\Contracts;

interface OperationContract
{
    public function isBounded(): bool;

    public function isBoundedTo(ContextContract $context): bool;
}

<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\ProperList;
use Webgraphe\Phlip\Operation;

abstract class PrimaryOperation extends Operation implements PrimaryOperationContract
{
    /**
     * @param ContextContract $context
     * @param FormContract[] $expressions
     * @return mixed
     */
    public final function __invoke(ContextContract $context, FormContract ...$expressions)
    {
        return $this->invoke($context, new ProperList(...$expressions));
    }

    /**
     * @param ContextContract $context
     * @param ProperList $expressions
     * @return mixed
     */
    abstract protected function invoke(ContextContract $context, ProperList $expressions);
}

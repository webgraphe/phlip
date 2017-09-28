<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * Mandatory for operations that require non-evaluated forms.
 * @see StandardOperationContract A mutually exclusive contract for operations that expect evaluated forms.
 */
interface PrimaryOperationContract extends OperationContract
{
    /**
     * @param ContextContract $context
     * @param ExpressionContract[] $expressions
     * @return mixed
     */
    public function __invoke(ContextContract $context, ExpressionContract ...$expressions);
}

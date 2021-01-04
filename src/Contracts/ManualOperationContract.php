<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * An operation that inherits this contract is in charge of evaluating every single form.
 */
interface ManualOperationContract extends OperationContract
{
    /**
     * @param ContextContract $context
     * @param FormContract ...$forms
     * @return mixed
     */
    public function __invoke(ContextContract $context, FormContract ...$forms);
}

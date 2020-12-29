<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * Mandatory for operations that require non-evaluated forms.
 * @see AutomaticOperationContract A mutually exclusive contract for operations that expect evaluated forms.
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

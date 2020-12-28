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

    /**
     * Walks the tail of a statement and returns the forms. For example, for (a b c), this method would walk b and c.
     * Convenient to protect some forms to be expanded.
     *
     * @param WalkerContract $walker
     * @param FormContract ...$forms
     * @return FormContract[]
     */
    public function walk(WalkerContract $walker, FormContract ...$forms): array;
}

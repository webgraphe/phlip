<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * Mandatory for operations that require its forms not to be evaluated automatically.
 * @see FunctionContract A mutually exclusive contract for operations that expect evaluated forms.
 */
interface LanguageConstructContract
{
    /**
     * @param ContextContract $context
     * @param ExpressionContract[] $expressions
     * @return mixed
     */
    public function __invoke(ContextContract $context, ExpressionContract ...$expressions);
}

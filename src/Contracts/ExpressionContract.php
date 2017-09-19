<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * A token extracted by a parser.
 */
interface ExpressionContract extends StringConvertibleContract
{
    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context);

    public function equals(ExpressionContract $against): bool;
}

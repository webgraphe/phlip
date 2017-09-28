<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * A token extracted by a parser.
 */
interface FormContract extends StringConvertibleContract
{
    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context);

    public function equals(FormContract $against): bool;
}

<?php

namespace Webgraphe\Phlip\Contracts;

use Webgraphe\Phlip\Exception\ContextException;

/**
 * An immutable object meant to be evaluated.
 */
interface FormContract extends StringConvertibleContract
{
    /**
     * @param ContextContract $context
     * @return mixed
     * @throws ContextException
     * @see ContextContract::execute() Should be the only caller
     */
    public function evaluate(ContextContract $context);

    public function equals(FormContract $against): bool;

    public function getCodeAnchor(): ?CodeAnchorContract;
}

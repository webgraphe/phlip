<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * An immutable object meant to be evaluated.
 */
interface FormContract extends StringConvertibleContract
{
    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context);

    public function equals(FormContract $against): bool;

    public function getCodeAnchor(): ?CodeAnchorContract;
}

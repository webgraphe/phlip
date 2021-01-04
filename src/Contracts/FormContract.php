<?php

namespace Webgraphe\Phlip\Contracts;

use Webgraphe\Phlip\Exception\ScopeException;

/**
 * An immutable object meant to be evaluated.
 */
interface FormContract extends StringConvertibleContract
{
    /**
     * @param ScopeContract $scope
     * @return mixed
     * @throws ScopeException
     * @see ScopeContract::execute() Should be the only caller
     */
    public function evaluate(ScopeContract $scope);

    public function equals(FormContract $against): bool;

    public function getCodeAnchor(): ?CodeAnchorContract;
}

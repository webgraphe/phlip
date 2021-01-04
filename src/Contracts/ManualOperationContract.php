<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * An operation that inherits this contract is in charge of evaluating every single form.
 * It is also given the scope in which it's invoked.
 */
interface ManualOperationContract extends OperationContract
{
    /**
     * @param ScopeContract $scope
     * @param FormContract ...$forms
     * @return mixed
     */
    public function __invoke(ScopeContract $scope, FormContract ...$forms);
}

<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\ManualOperationContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation;

abstract class ManualOperation extends Operation implements ManualOperationContract
{
    /**
     * @param ScopeContract $scope
     * @param FormContract ...$forms
     * @return mixed
     */
    public final function __invoke(ScopeContract $scope, FormContract ...$forms)
    {
        return $this->invoke($scope, new FormList(...$forms));
    }

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     */
    abstract protected function invoke(ScopeContract $scope, FormList $forms);
}

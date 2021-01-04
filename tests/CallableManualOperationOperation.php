<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\ManualOperationContract;
use Webgraphe\Phlip\FormCollection\FormList;

class CallableManualOperationOperation implements ManualOperationContract
{
    /** @var callable */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param ScopeContract $scope
     * @param FormContract[] $forms
     * @return mixed
     */
    public function __invoke(ScopeContract $scope, FormContract ...$forms)
    {
        return call_user_func($this->callback, $scope, new FormList(...$forms));
    }
}

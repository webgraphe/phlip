<?php

namespace Webgraphe\Phlip;

use Closure;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\ManualOperationContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\LanguageConstruct\LambdaOperation;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class Macro implements ManualOperationContract
{
    use AssertsStaticType;

    /** @var Closure */
    private $lambda;

    /**
     * @param ScopeContract $scope
     * @param FormList $parameters
     * @param FormContract $body
     * @throws Exception\AssertionException
     */
    public function __construct(ScopeContract $scope, FormList $parameters, FormContract $body)
    {
        $this->lambda = LambdaOperation::invokeStatic($scope, $parameters, $body);
    }

    /**
     * @param FormList $body
     * @param FormBuilder|null $formBuilder
     * @return FormContract
     * @throws Exception\AssertionException
     */
    public function expand(FormList $body, FormBuilder $formBuilder = null): FormContract
    {
        $formBuilder = $formBuilder ?? new FormBuilder();

        return $formBuilder->asForm(call_user_func($this->lambda, ...FormList::asList($body)));
    }

    /**
     * @param ScopeContract $scope
     * @param FormContract ...$forms
     * @return mixed
     * @throws Exception\AssertionException
     */
    public function __invoke(ScopeContract $scope, FormContract ...$forms)
    {
        return $scope->execute($this->expand(new FormList(...$forms)));
    }
}

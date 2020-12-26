<?php

namespace Webgraphe\Phlip;

use Closure;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\LanguageConstruct\LambdaOperation;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class Macro
{
    use AssertsStaticType;

    /** @var Closure */
    private $lambda;

    /**
     * @param ContextContract $context
     * @param ProperList $parameters
     * @param FormContract $body
     * @throws Exception\AssertionException
     */
    public function __construct(ContextContract $context, ProperList $parameters, FormContract $body)
    {
        $this->lambda = LambdaOperation::invokeStatic($context, $parameters, $body);
    }

    /**
     * @param ContextContract $context
     * @param ProperList $body
     * @param FormBuilder|null $formBuilder
     * @return FormContract
     * @throws Exception\AssertionException
     */
    public function expand(ContextContract $context, ProperList $body, FormBuilder $formBuilder = null): FormContract
    {
        $formBuilder = $formBuilder ?? new FormBuilder();

        return $formBuilder->asForm($context, call_user_func($this->lambda, ...ProperList::asList($body)->all()));
    }
}

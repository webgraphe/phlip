<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\LanguageConstruct\LambdaOperation;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class Macro
{
    use AssertsStaticType;

    /** @var \Closure */
    private $lambda;

    public function __construct(ContextContract $context, ProperList $parameters, FormContract $body)
    {
        $this->lambda = LambdaOperation::invokeStatic($context, $parameters, $body);
    }

    public function expand(ProperList $form, FormBuilder $formBuilder = null): FormContract
    {
        $formBuilder = $formBuilder ?? new FormBuilder;

        return $formBuilder->asForm(call_user_func($this->lambda, ...ProperList::asList($form)->all()));
    }
}

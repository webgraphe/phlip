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

    /** @var ProperList */
    private $parameters;
    /** @var ProperList */
    private $body;

    public function __construct(ProperList $parameters, FormContract $body)
    {
        $this->parameters = $parameters;
        $this->body = $body;
    }

    public function expand(ContextContract $context, ProperList $form): FormContract
    {
        $formBuilder = new FormBuilder;

        return $formBuilder->asForm(
            call_user_func(
                LambdaOperation::invokeStatic($context, $this->parameters, new ProperList($this->body)),
                ...ProperList::asList($form)->all()
            )
        );
    }
}

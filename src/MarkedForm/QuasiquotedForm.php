<?php

namespace Webgraphe\Phlip\MarkedForm;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Symbol;

class QuasiquotedForm extends MarkedForm
{
    /**
     * @param ContextContract $context
     * @return FormContract
     * @throws AssertionException
     */
    public function evaluate(ContextContract $context): FormContract
    {
        return $this->apply($context, $this->getForm());
    }

    /**
     * @param ContextContract $context
     * @param FormContract $form
     * @return FormContract
     * @throws AssertionException
     * @throws ContextException
     */
    protected function apply(ContextContract $context, FormContract $form): FormContract
    {
        if ($form instanceof UnquotedForm) {
            return (new FormBuilder)->asForm($form->evaluate($context));
        }

        if ($form instanceof FormCollection) {
            return $form->map(
                function (FormContract $form) use ($context) {
                    return $this->apply($context, $form);
                }
            );
        }

        return $form;
    }

    public function getMarkSymbol(): Symbol\Mark
    {
        return Symbol\Mark\QuasiquoteSymbol::instance();
    }
}

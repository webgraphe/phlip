<?php

namespace Webgraphe\Phlip\MarkedForm;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Symbol;

class QuasiquotedForm extends MarkedForm
{
    /**
     * @param ContextContract $context
     * @return FormContract
     */
    public function evaluate(ContextContract $context): FormContract
    {
        return $this->apply($context, $this->getForm());
    }

    protected function apply(ContextContract $context, FormContract $form): FormContract
    {
        if ($form instanceof UnquotedForm) {
            return $form->evaluate($context);
        }

        if ($form instanceof FormCollection) {
            return $form->map(
                function(FormContract $form) use ($context) {
                    return $this->apply($context, $form);
                }
            );
        }

        return $form;
    }

    protected function getMarkSymbol(): Symbol\Mark
    {
        return Symbol\Mark\GraveAccentSymbol::instance();
    }
}

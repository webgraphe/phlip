<?php

namespace Webgraphe\Phlip\MarkedForm;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Symbol;

class QuotedForm extends MarkedForm
{
    /**
     * @param ContextContract $context
     * @return FormContract
     */
    public function evaluate(ContextContract $context): FormContract
    {
        return $this->getForm();
    }

    protected function getMarkSymbol(): Symbol\Mark
    {
        return Symbol\Mark\StraightSingleMarkSymbol::instance();
    }
}

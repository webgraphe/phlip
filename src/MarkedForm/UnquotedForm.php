<?php

namespace Webgraphe\Phlip\MarkedForm;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Symbol;

class UnquotedForm extends MarkedForm
{
    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return $context->execute($this->getForm());
    }

    public function getMarkSymbol(): Symbol\Mark
    {
        return Symbol\Mark\TildeSymbol::instance();
    }
}

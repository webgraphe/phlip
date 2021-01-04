<?php

namespace Webgraphe\Phlip\MarkedForm;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Symbol;

class QuotedForm extends MarkedForm
{
    /**
     * @param ScopeContract $scope
     * @return FormContract
     */
    public function evaluate(ScopeContract $scope): FormContract
    {
        return $this->getForm();
    }

    public function getMarkSymbol(): Symbol\Mark
    {
        return Symbol\Mark\QuoteSymbol::instance();
    }
}

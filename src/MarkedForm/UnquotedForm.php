<?php

namespace Webgraphe\Phlip\MarkedForm;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Symbol;

class UnquotedForm extends MarkedForm
{
    /**
     * @param ScopeContract $scope
     * @return mixed
     */
    public function evaluate(ScopeContract $scope)
    {
        return $scope->execute($this->getForm());
    }

    public function getMarkSymbol(): Symbol\Mark
    {
        return Symbol\Mark\UnquoteSymbol::instance();
    }
}

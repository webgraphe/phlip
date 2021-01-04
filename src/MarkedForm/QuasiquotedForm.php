<?php

namespace Webgraphe\Phlip\MarkedForm;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Symbol;

class QuasiquotedForm extends MarkedForm
{
    /**
     * @param ScopeContract $scope
     * @return FormContract
     * @throws AssertionException
     */
    public function evaluate(ScopeContract $scope): FormContract
    {
        return $this->apply($scope, $this->getForm());
    }

    /**
     * @param ScopeContract $scope
     * @param FormContract $form
     * @return FormContract
     * @throws AssertionException
     */
    protected function apply(ScopeContract $scope, FormContract $form): FormContract
    {
        if ($form instanceof UnquotedForm) {
            return (new FormBuilder())->asForm($scope->execute($form));
        }

        if ($form instanceof FormCollection) {
            return $form->map(
                function (FormContract $form) use ($scope) {
                    return $this->apply($scope, $form);
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

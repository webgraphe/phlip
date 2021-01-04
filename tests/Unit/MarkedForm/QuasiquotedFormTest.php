<?php

namespace Webgraphe\Phlip\Tests\Unit\MarkedForm;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Scope;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Tests\Unit\MarkedFormTest;

class QuasiquotedFormTest extends MarkedFormTest
{
    /**
     * @param CodeAnchorContract|null $codeAnchor
     * @return FormContract
     * @throws AssertionException
     */
    protected function createForm(CodeAnchorContract $codeAnchor = null): FormContract
    {
        return new FormList(
            IdentifierAtom::fromString('+', $codeAnchor),
            NumberAtom::fromString('2'),
            NumberAtom::fromString('3')
        );
    }

    /**
     * @return ScopeContract
     * @throws ScopeException
     */
    private function getScope(): ScopeContract
    {
        $scope = new Scope();
        $scope->define('x', 2);
        $scope->define('y', 3);

        return $scope;
    }

    /**
     * @return FormContract
     * @throws AssertionException
     */
    private function getFormWithUnquotedIdentifiers(): FormContract
    {
        return new FormList(
            IdentifierAtom::fromString('+'),
            new MarkedForm\UnquotedForm(IdentifierAtom::fromString('x')),
            new MarkedForm\UnquotedForm(IdentifierAtom::fromString('y'))
        );
    }

    protected function createMarkedForm(FormContract $form): MarkedForm
    {
        return new MarkedForm\QuasiquotedForm($form);
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testEvaluation()
    {
        $this->assertTrue(
            $this->createForm()->equals(
                $this->getScope()->execute($this->createMarkedForm($this->getFormWithUnquotedIdentifiers()))
            )
        );
    }

}

<?php

namespace Webgraphe\Phlip\Tests\Unit\MarkedForm;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Scope;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Tests\Unit\MarkedFormTest;

class UnquotedFormTest extends MarkedFormTest
{
    /**
     * @param CodeAnchorContract|null $codeAnchor
     * @return FormContract
     * @throws AssertionException
     */
    protected function createForm(CodeAnchorContract $codeAnchor = null): FormContract
    {
        return IdentifierAtom::fromString('x', $codeAnchor);
    }

    protected function createMarkedForm(FormContract $form): MarkedForm
    {
        return new MarkedForm\UnquotedForm($form);
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testEvaluation()
    {
        $scope = new Scope();
        $scope->define('x', 3);
        $this->assertEquals(3, $scope->execute($this->createMarkedForm($this->createForm())));
    }
}

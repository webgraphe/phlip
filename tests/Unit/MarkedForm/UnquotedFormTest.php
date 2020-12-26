<?php

namespace Webgraphe\Phlip\Tests\Unit\MarkedForm;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
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
     * @throws ContextException
     */
    public function testEvaluation()
    {
        $context = new Context();
        $context->define('x', 3);
        $this->assertEquals(3, $context->execute($this->createMarkedForm($this->createForm())));
    }
}

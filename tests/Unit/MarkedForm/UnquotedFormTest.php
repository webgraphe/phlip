<?php

namespace Webgraphe\Phlip\Tests\Unit\MarkedForm;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Tests\Unit\MarkedFormTest;

class UnquotedFormTest extends MarkedFormTest
{
    /**
     * @param CodeAnchorContract|null $codeAnchor
     * @return FormContract
     * @throws \Webgraphe\Phlip\Exception\AssertionException
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
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     * @throws \Webgraphe\Phlip\Exception\ContextException
     */
    public function testEvaluation()
    {
        $context = new Context;
        $context->define('x', 3);
        $this->assertEquals(3, $this->createMarkedForm($this->createForm())->evaluate($context));
    }
}

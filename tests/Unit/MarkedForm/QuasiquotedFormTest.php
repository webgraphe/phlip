<?php

namespace Webgraphe\Phlip\Tests\Unit\MarkedForm;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Tests\Unit\MarkedFormTest;

class QuasiquotedFormTest extends MarkedFormTest
{
    /**
     * @param CodeAnchorContract|null $codeAnchor
     * @return FormContract
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    protected function createForm(CodeAnchorContract $codeAnchor = null): FormContract
    {
        return new ProperList(
            IdentifierAtom::fromString('+', $codeAnchor),
            NumberAtom::fromString('2'),
            NumberAtom::fromString('3')
        );
    }

    /**
     * @return ContextContract
     * @throws \Webgraphe\Phlip\Exception\ContextException
     */
    private function getContext(): ContextContract
    {
        $context = new Context;
        $context->define('x', 2);
        $context->define('y', 3);

        return $context;
    }

    /**
     * @return FormContract
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    private function getFormWithUnquotedIdentifiers(): FormContract
    {
        return new ProperList(
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
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     * @throws \Webgraphe\Phlip\Exception\ContextException
     */
    public function testEvaluation()
    {
        $this->assertTrue(
            $this->createForm()->equals(
                $this->createMarkedForm($this->getFormWithUnquotedIdentifiers())->evaluate($this->getContext())
            )
        );
    }

}

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
    protected function createForm(CodeAnchorContract $codeAnchor = null): FormContract
    {
        return new ProperList(
            IdentifierAtom::fromString('+', $codeAnchor),
            NumberAtom::fromString('2'),
            NumberAtom::fromString('3')
        );
    }

    private function getContext(): ContextContract
    {
        $context = new Context;
        $context->define('x', 2);
        $context->define('y', 3);

        return $context;
    }

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

    public function testEvaluation()
    {
        $this->assertTrue(
            $this->createForm()->equals(
                $this->createMarkedForm($this->getFormWithUnquotedIdentifiers())->evaluate($this->getContext())
            )
        );
    }

}

<?php

namespace Webgraphe\Phlip\Tests\Unit\MarkedForm;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Tests\Unit\MarkedFormTest;

class QuotedFormTest extends MarkedFormTest
{
    protected function createForm(CodeAnchorContract $codeAnchor = null): FormContract
    {
        return new ProperList(
            IdentifierAtom::fromString('+', $codeAnchor),
            NumberAtom::fromString('2'),
            NumberAtom::fromString('3')
        );
    }

    protected function createMarkedForm(FormContract $form): MarkedForm
    {
        return new MarkedForm\QuotedForm($form);
    }

    public function testEvaluation()
    {
        $this->assertTrue(
            $this->createForm()->equals($this->createMarkedForm($this->createForm())->evaluate(new Context))
        );
    }
}
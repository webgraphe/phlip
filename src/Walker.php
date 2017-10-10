<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;

class Walker
{
    public function apply(ContextContract $context, FormContract $form): FormContract
    {
        if ($form instanceof QuotedForm) {
            return new QuotedForm($this->apply($context, $form->getForm()));
        }

        if (!($form instanceof ProperList) || !count($form)) {
            return $form;
        }

        $head = $form->assertHead();

        if (!($head instanceof IdentifierAtom)) {
            return $form;
        }

        if (!$context->has($head->getValue())) {
            return $form;
        }

        $macro = $context->get($head->getValue());
        if (!($macro instanceof Macro)) {
            return new ProperList(
                $head,
                ...array_map(
                    function (FormContract $form) use ($context) {
                        return $this->apply($context, $form);
                    },
                    $form->getTail()->all()
                )
            );
        }

        return $this->apply($context, $macro->expand($context, $form->getTail()));
    }
}

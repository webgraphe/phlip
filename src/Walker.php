<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;

class Walker
{
    public function apply(ContextContract $context, FormContract $form, FormBuilder $formBuilder = null): FormContract
    {
        if ($form instanceof MarkedForm) {
            return $form->createNew($this->apply($context, $form->getForm(), $formBuilder));
        }

        if (!($form instanceof ProperList) || !count($form)) {
            return $form;
        }

        $head = $form->assertHead();

        if (!($head instanceof IdentifierAtom) || !$context->has($head->getValue())) {
            return $form;
        }

        $definition = $context->get($head->getValue());
        if ($definition instanceof Macro) {
            return $this->apply($context, $definition->expand($form->getTail(), $formBuilder), $formBuilder);
        }

        return new ProperList(
            $head,
            ...array_map(
                function (FormContract $form) use ($context, $formBuilder) {
                    return $this->apply($context, $form, $formBuilder);
                },
                $form->getTail()->all()
            )
        );
    }
}

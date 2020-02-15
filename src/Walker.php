<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\FormCollection\ProperList;

class Walker implements WalkerContract
{
    private FormBuilder $formBuilder;
    private ContextContract $context;

    public function __construct(ContextContract $context, FormBuilder $formBuilder = null)
    {
        $this->context = $context;
        $this->formBuilder = $formBuilder ?? new FormBuilder;
    }

    /**
     * @param FormContract $form
     * @return FormContract
     * @throws Exception\AssertionException
     */
    public function __invoke(FormContract $form): FormContract
    {
        if ($form instanceof MarkedForm) {
            return $form->createNew($this($form->getForm()));
        }

        if (!($form instanceof ProperList) || !count($form)) {
            return $form;
        }

        $head = $form->assertHead();

        if (!($head instanceof IdentifierAtom) || !$this->context->has($head->getValue())) {
            return $form;
        }

        $definition = $this->context->get($head->getValue());

        if ($definition instanceof Macro) {
            return $this($definition->expand($form->getTail(), $this->formBuilder));
        }

        return $definition instanceof PrimaryOperationContract
            ? new ProperList($head, ...$definition->walk($this, ...$form->getTail()->all()))
            : $form->map($this);
    }
}

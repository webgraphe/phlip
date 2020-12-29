<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\ManualOperationContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\FormCollection\ProperList;

class Walker implements WalkerContract
{
    /** @var FormBuilder */
    private $formBuilder;
    /** @var ContextContract */
    private $context;

    public function __construct(ContextContract $context, FormBuilder $formBuilder = null)
    {
        $this->context = $context;
        $this->formBuilder = $formBuilder ?? new FormBuilder();
    }

    /**
     * @param FormContract $form
     * @return FormContract
     * @throws Exception\AssertionException
     * @throws Exception\ContextException
     */
    public function __invoke(FormContract $form): FormContract
    {
        if ($form instanceof MarkedForm || !($form instanceof ProperList) || !count($form)) {
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

        return $definition instanceof ManualOperationContract
            ? new ProperList($head, ...$definition->walk($this, ...$form->getTail()))
            : $form->map($this);
    }
}

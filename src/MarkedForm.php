<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;

abstract class MarkedForm implements FormContract
{
    /** @var FormContract */
    private $form;

    final public function __construct(FormContract $form)
    {
        $this->form = $form;
    }

    public function __toString(): string
    {
        return $this->getMarkSymbol()->getValue() . (string)$this->form;
    }

    /**
     * @param FormContract $form
     * @return static
     */
    public function createNew(FormContract $form): MarkedForm
    {
        return new static($form);
    }

    public function equals(FormContract $against): bool
    {
        return $against instanceof static && $this->form->equals($against->form);
    }

    /**
     * @return FormContract
     */
    public function getForm(): FormContract
    {
        return $this->form;
    }

    public function getCodeAnchor(): ?CodeAnchorContract
    {
        return $this->form->getCodeAnchor();
    }

    abstract public function getMarkSymbol(): Symbol\Mark;
}

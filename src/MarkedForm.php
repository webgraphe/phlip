<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;

abstract class MarkedForm implements FormContract
{
    /** @var FormContract */
    private $form;
    /** @var CodeAnchorContract */
    private $codeAnchor;

    final public function __construct(FormContract $form, CodeAnchorContract $codeAnchor = null)
    {
        $this->form = $form;
        $this->codeAnchor = $codeAnchor;
    }

    public function __toString(): string
    {
        return $this->getMarkSymbol()->getValue() . (string)$this->form;
    }

    /**
     * @param FormContract $form
     * @param CodeAnchorContract|null $codeAnchor
     * @return static
     */
    public function createNew(FormContract $form, CodeAnchorContract $codeAnchor = null): MarkedForm
    {
        return new static($form, $codeAnchor ?? $this->codeAnchor);
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
        return $this->codeAnchor;
    }

    abstract protected function getMarkSymbol(): Symbol\Mark;
}

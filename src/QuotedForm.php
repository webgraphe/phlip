<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;

class QuotedForm implements FormContract
{
    /** @var FormContract */
    private $form;
    /** @var CodeAnchorContract */
    private $codeAnchor;

    public function __construct(FormContract $form, CodeAnchorContract $codeAnchor = null)
    {
        $this->form = $form;
        $this->codeAnchor = $codeAnchor;
    }

    /**
     * @param ContextContract $context
     * @return FormContract
     */
    public function evaluate(ContextContract $context): FormContract
    {
        return $this->form;
    }

    public function __toString(): string
    {
        return "'" . (string)$this->form;
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
}

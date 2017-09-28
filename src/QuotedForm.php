<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;

class QuotedForm implements FormContract
{
    /** @var FormContract */
    private $form;

    public function __construct(FormContract $form)
    {
        $this->form = $form;
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
}

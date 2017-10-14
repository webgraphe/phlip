<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Traits\AssertsStaticType;

abstract class Atom implements LexemeContract, FormContract
{
    use AssertsStaticType;

    /** @var string|number|bool|null */
    private $value;
    /** @var CodeAnchorContract|null */
    private $codeAnchor;

    protected function __construct($value, CodeAnchorContract $codeAnchor = null)
    {
        $this->value = $value;
        $this->codeAnchor = $codeAnchor;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function equals(FormContract $against): bool
    {
        return $against instanceof static && $against->getValue() === $this->getValue();
    }

    public function getCodeAnchor(): ?CodeAnchorContract
    {
        return $this->codeAnchor;
    }
}

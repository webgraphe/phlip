<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Traits\AssertsStaticType;

abstract class Atom implements LexemeContract, FormContract
{
    use AssertsStaticType;

    /** @var string|number|bool|null */
    private $value;

    protected function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function equals(FormContract $against): bool
    {
        return $against instanceof static && $against->getValue() === $this->getValue();
    }
}

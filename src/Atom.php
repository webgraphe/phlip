<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Traits\AssertsStaticType;

abstract class Atom implements LexemeContract, ExpressionContract
{
    use AssertsStaticType;

    /** @var string|number|bool|null */
    private $value;

    protected function __construct($value)
    {
        $this->value = $value;
    }

    public final function getValue()
    {
        return $this->value;
    }

    public function equals(ExpressionContract $against): bool
    {
        return $against instanceof static && $against->getValue() === $this->getValue();
    }
}

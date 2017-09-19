<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class IdentifierAtom extends Atom
{
    public function __construct($value)
    {
        parent::__construct($value);
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return $context->get($this->getValue());
    }

    /**
     * @return string|number|bool|null
     */
    public function getValue()
    {
        return $this->getOriginalValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function greaterThan(Atom $other): bool
    {
        return false;
    }

    public function lesserThan(Atom $other): bool
    {
        return false;
    }
}

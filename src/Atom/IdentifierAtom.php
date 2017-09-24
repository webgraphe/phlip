<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class IdentifierAtom extends Atom
{
    public function __construct(string $value)
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

    public function __toString(): string
    {
        return $this->getValue();
    }
}

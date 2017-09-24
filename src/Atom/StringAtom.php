<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class StringAtom extends Atom
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function evaluate(ContextContract $context): string
    {
        return $this->getValue();
    }

    public function __toString(): string
    {
        return '"' . str_replace('"', '\\"', $this->getValue()) . '"';
    }
}

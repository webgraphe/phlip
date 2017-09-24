<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class NumberAtom extends Atom
{
    /**
     * @param string|number $value
     */
    public function __construct($value)
    {
        parent::__construct(0 + $value);
    }

    public static function isNumber($lexeme): bool
    {
        return is_numeric($lexeme);
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }

    /**
     * @param ContextContract $context
     * @return number
     */
    public function evaluate(ContextContract $context)
    {
        return $this->getValue();
    }
}

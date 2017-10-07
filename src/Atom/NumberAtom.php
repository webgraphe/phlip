<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;

class NumberAtom extends Atom
{
    /**
     * @param string|number $value
     * @param CodeAnchorContract|null $codeAnchor
     * @return NumberAtom
     */
    public static function fromString(string $value, CodeAnchorContract $codeAnchor = null): NumberAtom
    {
        return new static($value + 0, $codeAnchor);
    }

    /**
     * @return number
     */
    public function getValue()
    {
        return parent::getValue();
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

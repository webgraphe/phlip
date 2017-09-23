<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class NumberAtom extends Atom
{
    /** @var number */
    private $number;

    /**
     * @param string|number $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
        $this->number = 0 + $value;
    }

    public static function isNumber($lexeme): bool
    {
        return is_numeric($lexeme);
    }

    /**
     * @return number
     */
    public function getValue()
    {
        return $this->number;
    }

    public function __toString(): string
    {
        return (string)$this->getOriginalValue();
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
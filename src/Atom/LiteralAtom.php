<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class LiteralAtom extends Atom
{
    /** @var number|null */
    private $number;

    public function __construct($value)
    {
        parent::__construct($value);

        if (is_numeric($value)) {
            $this->number = $value + 0;
        }
    }

    public function isNumber()
    {
        return null !== $this->number;
    }

    /**
     * @return number|null
     */
    public function getNumberValue()
    {
        return $this->number;
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return $this->isNumber() ? $this->getNumberValue() : $this->getValue();
    }

    public function __toString(): string
    {
        $string = parent::__toString();

        return $this->isNumber() ? $string : '"' . str_replace('"', '\\"', $string) . '"';
    }
}

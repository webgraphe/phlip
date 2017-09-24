<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class BooleanAtom extends Atom
{
    public static function isBoolean(string $lexeme): bool
    {
        return true === $lexeme || 'true' === $lexeme || false === $lexeme || 'false' === $lexeme;
    }

    public static function true(): BooleanAtom
    {
        static $true;

        if (!$true) {
            $true = new static(true);
        }

        return $true;
    }

    public static function false(): BooleanAtom
    {
        static $false;

        if (!$false) {
            $false = new static(false);
        }

        return $false;
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return $this->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue() ? 'true' : 'false';
    }
}

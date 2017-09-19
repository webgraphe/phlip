<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class BooleanAtom extends Atom
{
    /** @var bool */
    private $boolean;

    public static function isBoolean(string $lexeme): bool
    {
        return 'true' === $lexeme || 'false' === $lexeme;
    }

    public static function true(): BooleanAtom
    {
        static $true;
        if (!$true) {
            $true = new self('true');
            $true->boolean = true;
        }

        return $true;
    }

    public static function false(): BooleanAtom
    {
        static $false;
        if (!$false) {
            $false = new self('false');
            $false->boolean = false;
        }

        return $false;
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return $this->boolean;
    }

    /**
     * @return string|number|bool|null
     */
    public function getValue()
    {
        return $this->boolean;
    }

    public function __toString(): string
    {
        return $this->boolean ? 'true' : 'false';
    }

    public function greaterThan(Atom $other): bool
    {
        if ($other instanceof static) {
            return (int)$this->boolean > (int)$other->boolean;
        }

        return false;
    }

    public function lesserThan(Atom $other): bool
    {
        if ($other instanceof static) {
            return (int)$this->boolean < (int)$other->boolean;
        }

        return false;
    }
}
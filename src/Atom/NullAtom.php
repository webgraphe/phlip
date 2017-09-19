<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class NullAtom extends Atom
{
    public static function instance()
    {
        static $instance;

        if (!$instance) {
            $instance = new self('null');
        }

        return $instance;
    }

    public static function isNull($lexeme)
    {
        return 'null' === $lexeme;
    }

    public function greaterThan(Atom $other): bool
    {
        return true;
    }

    public function lesserThan(Atom $other): bool
    {
        return false;
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return null;
    }

    /**
     * @return string|number|bool|null
     */
    public function getValue()
    {
        return null;
    }

    public function __toString(): string
    {
        return 'null';
    }
}

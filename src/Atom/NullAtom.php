<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class NullAtom extends Atom
{
    public static function instance(): NullAtom
    {
        static $instance;

        return $instance ?? ($instance = new self('null'));
    }

    public static function isNull($lexeme)
    {
        return null === $lexeme || 'null' === $lexeme;
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

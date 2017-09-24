<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;

class NullAtom extends Atom
{
    public static function instance(): NullAtom
    {
        static $instance;
        if (!$instance) {
            $instance = new static(null);
        }

        return $instance;
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

    public function __toString(): string
    {
        return 'null';
    }
}

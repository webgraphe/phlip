<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;

class KeywordAtom extends Atom
{
    private static $instances = [];

    public static function fromString(string $value): KeywordAtom
    {
        if (!strlen($value)) {
            throw new AssertionException('Keyword is empty');
        }
        return self::$instances[$value] ?? (self::$instances[$value] = new static($value));
    }

    public static function fromIdentifierAtom(IdentifierAtom $identifier): KeywordAtom
    {
        return static::fromString($identifier->getValue());
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return $this;
    }

    public function __toString(): string
    {
        return ':' . $this->getValue();
    }
}
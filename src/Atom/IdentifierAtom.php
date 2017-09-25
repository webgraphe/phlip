<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;

class IdentifierAtom extends Atom
{
    const IDENTIFIER_REGEX = '/^[^:\s\'\(\)\[\]\{\}]+$/';

    /**
     * @param string $value
     * @return IdentifierAtom
     * @throws AssertionException
     */
    public static function fromString(string $value): IdentifierAtom
    {
        if (!preg_match(self::IDENTIFIER_REGEX, $value)) {
            throw new AssertionException('Invalid identifier');
        }

        return new static($value);
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        return $context->get($this->getValue());
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}

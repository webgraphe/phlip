<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;

class IdentifierAtom extends Atom
{
    /**
     * @param string $value
     * @param CodeAnchorContract|null $codeAnchor
     * @return IdentifierAtom
     * @throws AssertionException
     */
    public static function fromString(string $value, CodeAnchorContract $codeAnchor = null): IdentifierAtom
    {
        return new static(static::assertValidIdentifier($value), $codeAnchor);
    }

    /**
     * @param ContextContract $context
     * @return mixed
     * @throws ContextException
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

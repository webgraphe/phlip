<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;

class IdentifierAtom extends Atom
{
    const IDENTIFIER_REGEX = '/^[^:\s\'\(\)\[\]\{\}]+$/';

    /**
     * @param string $value
     * @param CodeAnchorContract|null $codeAnchor
     * @return IdentifierAtom
     * @throws AssertionException
     */
    public static function fromString(string $value, CodeAnchorContract $codeAnchor = null): IdentifierAtom
    {
        if (!preg_match(self::IDENTIFIER_REGEX, $value)) {
            throw new AssertionException('Invalid identifier');
        }

        return new static($value, $codeAnchor);
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

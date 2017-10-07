<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Symbol\KeywordSymbol;

class KeywordAtom extends Atom
{
    public static function fromString(string $value, CodeAnchorContract $codeAnchor = null): KeywordAtom
    {
        if (strlen($value) && KeywordSymbol::CHARACTER === $value[0]) {
            $value = substr($value, 1);
        }
        if (!strlen($value)) {
            throw new AssertionException('Keyword is empty');
        }

        return new static($value, $codeAnchor);
    }

    public static function fromIdentifierAtom(
        IdentifierAtom $identifier,
        CodeAnchorContract $codeAnchor = null
    ): KeywordAtom {
        return static::fromString($identifier->getValue(), $codeAnchor);
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

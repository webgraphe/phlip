<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Symbol\KeywordSymbol;

class KeywordAtom extends Atom
{
    /**
     * @param string $value
     * @param CodeAnchorContract|null $codeAnchor
     * @return KeywordAtom
     * @throws AssertionException
     */
    public static function fromString(string $value, CodeAnchorContract $codeAnchor = null): KeywordAtom
    {
        return new static(static::assertNormalizedKeyword($value), $codeAnchor);
    }

    /**
     * @param ScopeContract $scope
     * @return mixed
     */
    public function evaluate(ScopeContract $scope)
    {
        return $this;
    }

    public function __toString(): string
    {
        return KeywordSymbol::instance()->getValue() . $this->getValue();
    }

    /**
     * @param string $value
     * @return string
     * @throws AssertionException
     */
    public static function assertNormalizedKeyword(string $value): string
    {
        if (strlen($value) && KeywordSymbol::CHARACTER === $value[0]) {
            $value = substr($value, 1);
        }

        return static::assertValidIdentifier($value);
    }
}

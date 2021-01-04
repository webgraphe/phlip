<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;

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
     * @param ScopeContract $scope
     * @return mixed
     * @throws ScopeException
     */
    public function evaluate(ScopeContract $scope)
    {
        return $scope->get($this->getValue());
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}

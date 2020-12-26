<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Operation;

class EqualityOperation extends Operation\Comparison
{
    /** @var string */
    const IDENTIFIER = '=';

    /**
     * @param array ...$arguments
     * @return bool
     * @throws AssertionException
     */
    public function __invoke(...$arguments): bool
    {
        $left = static::assertNativeValue(array_shift($arguments));
        $right = static::assertNativeValue(array_shift($arguments));

        return $left === $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

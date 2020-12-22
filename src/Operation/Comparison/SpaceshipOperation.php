<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Operation;

class SpaceshipOperation extends Operation\Comparison
{
    /** @var string */
    const IDENTIFIER = '<=>';

    /**
     * @param array ...$arguments
     * @return int
     * @throws AssertionException
     */
    public function __invoke(...$arguments): int
    {
        $left = self::assertNativeValue(array_shift($arguments));
        $right = self::assertNativeValue(array_shift($arguments));

        return $left <=> $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

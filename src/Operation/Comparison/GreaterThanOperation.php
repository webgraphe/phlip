<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Operation;

class GreaterThanOperation extends Operation\Comparison
{
    /** @var string */
    const IDENTIFIER = '>';
    /** @var string */
    const IDENTIFIER_ALTERNATIVE= 'gt?';

    /**
     * @param array ...$arguments
     * @return bool
     * @throws AssertionException
     */
    public function __invoke(...$arguments): bool
    {
        $left = self::assertNativeValue(array_shift($arguments));
        $right = self::assertNativeValue(array_shift($arguments));

        return $left > $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

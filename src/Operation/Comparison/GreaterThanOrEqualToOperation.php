<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Operation;

class GreaterThanOrEqualToOperation extends Operation\Comparison
{
    /** @var string */
    const IDENTIFIER = '>=';
    /** @var string */
    const IDENTIFIER_ALTERNATIVE= 'gte?';

    /**
     * @param array ...$arguments
     * @return mixed
     * @throws AssertionException
     */
    public function __invoke(...$arguments)
    {
        $left = self::assertNativeValue(array_shift($arguments));
        $right = self::assertNativeValue(array_shift($arguments));

        return $left >= $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

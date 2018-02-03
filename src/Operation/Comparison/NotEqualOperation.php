<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Operation;

class NotEqualOperation extends Operation\Comparison
{
    const IDENTIFIER = '!=';
    const IDENTIFIER_ALTERNATIVE = 'neq?';

    /**
     * @param array ...$arguments
     * @return bool|mixed
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    public function __invoke(...$arguments)
    {
        $left = self::assertNativeValue(array_shift($arguments));
        $right = self::assertNativeValue(array_shift($arguments));

        return $left !== $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

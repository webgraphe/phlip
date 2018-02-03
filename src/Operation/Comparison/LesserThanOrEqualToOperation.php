<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Operation;

class LesserThanOrEqualToOperation extends Operation\Comparison
{
    const IDENTIFIER = '<=';
    const IDENTIFIER_ALTERNATIVE= 'lte?';

    /**
     * @param array ...$arguments
     * @return mixed
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    public function __invoke(...$arguments)
    {
        $left = self::assertNativeValue(array_shift($arguments));
        $right = self::assertNativeValue(array_shift($arguments));

        return $left <= $right;

    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

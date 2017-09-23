<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class GreaterThanOrEqualToOperation extends Operation\Comparison
{
    const IDENTIFIER = '>=';
    const IDENTIFIER_ALTERNATIVE= 'gte?';

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $left = self::assertValue(array_shift($arguments));
        $right = self::assertValue(array_shift($arguments));

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

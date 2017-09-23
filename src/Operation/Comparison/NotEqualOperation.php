<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class NotEqualOperation extends Operation\Comparison
{
    const IDENTIFIER = '!=';
    const IDENTIFIER_ALTERNATIVE = 'neq?';

    public function __invoke(...$arguments)
    {
        $left = self::assertValue(array_shift($arguments));
        $right = self::assertValue(array_shift($arguments));

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

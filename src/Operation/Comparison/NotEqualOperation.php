<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class NotEqualOperation extends Operation implements FunctionContract
{
    const IDENTIFIER = '!=';
    const IDENTIFIER_ALTERNATIVE = 'neq?';

    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        if ($left instanceof ExpressionContract && $right instanceof ExpressionContract) {
            return !$left->equals($right);
        }

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

<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Operation\Type;

class IsExpressionOperation extends Type
{
    const IDENTIFIER = 'expression?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof ExpressionContract;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

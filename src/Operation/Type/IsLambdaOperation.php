<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Operation\StandardOperation;

class IsLambdaOperation extends StandardOperation
{
    const IDENTIFIER = 'lambda?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof \Closure;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

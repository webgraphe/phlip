<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Collection\Vector;
use Webgraphe\Phlip\Operation\Type;

class IsLambdaOperation extends Type
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

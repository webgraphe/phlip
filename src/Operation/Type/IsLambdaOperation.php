<?php

namespace Webgraphe\Phlip\Operation\Type;

use Closure;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsLambdaOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = 'lambda?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof Closure;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

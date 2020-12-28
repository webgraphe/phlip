<?php

namespace Webgraphe\Phlip\Operation\Type;

use Closure;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsLambdaOperation extends AutomaticOperation
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

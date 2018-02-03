<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Operation\StandardOperation;

class IsCallableOperation extends StandardOperation
{
    const IDENTIFIER = 'callable?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return is_callable($argument);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

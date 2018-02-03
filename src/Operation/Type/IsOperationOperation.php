<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Contracts\OperationContract;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsOperationOperation extends StandardOperation
{
    const IDENTIFIER = 'operation?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof OperationContract;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

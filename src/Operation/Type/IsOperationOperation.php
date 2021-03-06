<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Contracts\OperationContract;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsOperationOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'operation?';

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

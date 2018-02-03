<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsListOperation extends StandardOperation
{
    const IDENTIFIER = 'list?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof ProperList;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

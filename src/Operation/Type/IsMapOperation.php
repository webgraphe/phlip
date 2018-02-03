<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsMapOperation extends StandardOperation
{
    const IDENTIFIER = 'map?';

    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof \stdClass || $argument instanceof Map;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

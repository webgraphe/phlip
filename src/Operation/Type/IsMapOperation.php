<?php

namespace Webgraphe\Phlip\Operation\Type;

use stdClass;
use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsMapOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'map?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof stdClass || $argument instanceof Map;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

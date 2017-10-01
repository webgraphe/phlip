<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Collection\Pair;
use Webgraphe\Phlip\Operation\Type;

class IsPairOperation extends Type
{
    const IDENTIFIER = 'pair?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof Pair;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

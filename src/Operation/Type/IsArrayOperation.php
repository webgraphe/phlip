<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Collection\Vector;
use Webgraphe\Phlip\Operation\Type;

class IsArrayOperation extends Type
{
    const IDENTIFIER = 'array?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return is_array($argument) || $argument instanceof Vector;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

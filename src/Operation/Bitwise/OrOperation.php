<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Operation\StandardOperation;

class OrOperation extends StandardOperation
{
    const IDENTIFIER = 'bit-or';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    public function __invoke(...$arguments): int
    {
        $field = array_shift($arguments);
        while ($arguments) {
            $field |= array_shift($arguments);
        }

        return $field;
    }
}

<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Operation\StandardOperation;

class ShiftLeftOperation extends StandardOperation
{
    const IDENTIFIER = 'bit-shift-left';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    public function __invoke(...$arguments): int
    {
        $a = array_shift($arguments);
        $b = array_shift($arguments);

        return $a << $b;
    }
}

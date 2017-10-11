<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class ShiftLeftOperation extends Operation implements StandardOperationContract
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

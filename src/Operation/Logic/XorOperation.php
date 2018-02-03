<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Operation\StandardOperation;

class XorOperation extends StandardOperation
{
    const IDENTIFIER = 'xor';

    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        return $left xor $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

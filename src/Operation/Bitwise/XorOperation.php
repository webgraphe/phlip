<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class XorOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'bit-xor';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    public function __invoke(...$arguments): int
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        return $left ^ $right;
    }
}

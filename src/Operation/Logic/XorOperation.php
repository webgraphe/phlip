<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class XorOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'xor';

    public function __invoke(...$arguments): bool
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

<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Operation\StandardOperation;

class NotOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = 'bit-not';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    public function __invoke(...$arguments): int
    {
        return ~array_shift($arguments);
    }
}

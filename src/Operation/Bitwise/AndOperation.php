<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Operation\StandardOperation;

class AndOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = 'bit-and';

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
        while ($field && $arguments) {
            $field &= array_shift($arguments);
        }

        return $field;
    }
}

<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class AndOperation extends AutomaticOperation
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

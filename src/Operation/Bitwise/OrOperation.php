<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class OrOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = '|';

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

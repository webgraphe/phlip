<?php

namespace Webgraphe\Phlip\Operation\Bitwise;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class AndOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = '&';

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

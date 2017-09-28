<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class AdditionOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = '+';
    const IDENTIFIER_ALTERNATIVE = 'add';

    public function __invoke(...$arguments)
    {
        return array_sum($arguments);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

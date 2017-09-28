<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class NotOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = '!';
    const IDENTIFIER_ALTERNATIVE = 'not';

    public function __invoke(...$arguments)
    {
        return !array_shift($arguments);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

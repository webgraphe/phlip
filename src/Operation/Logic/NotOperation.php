<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Operation\StandardOperation;

class NotOperation extends StandardOperation
{
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
        return [self::IDENTIFIER_ALTERNATIVE];
    }
}

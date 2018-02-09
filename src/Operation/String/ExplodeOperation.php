<?php

namespace Webgraphe\Phlip\Operation\String;

use Webgraphe\Phlip\Operation\StandardOperation;

class ExplodeOperation extends StandardOperation
{
    const IDENTIFIER = 'explode';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        return explode(...$arguments);
    }
}

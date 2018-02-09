<?php

namespace Webgraphe\Phlip\Operation\String;

use Webgraphe\Phlip\Operation\StandardOperation;

class SubstringOperation extends StandardOperation
{
    const IDENTIFIER = 'substring';

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
        return substr(...$arguments);
    }
}

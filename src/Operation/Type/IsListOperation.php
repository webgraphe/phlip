<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\Type;

class IsListOperation extends Type
{
    const IDENTIFIER = 'list?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof FormList;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

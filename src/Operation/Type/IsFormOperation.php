<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsFormOperation extends StandardOperation
{
    const IDENTIFIER = 'form?';

    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof FormContract;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

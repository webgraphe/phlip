<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsNumberOperation extends StandardOperation
{
    const IDENTIFIER = 'number?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return is_numeric($argument) || $argument instanceof NumberAtom;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

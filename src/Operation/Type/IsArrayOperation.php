<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Atom\ArrayAtom;
use Webgraphe\Phlip\Operation\Type;

class IsArrayOperation extends Type
{
    const IDENTIFIER = 'array?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return is_array($argument) || $argument instanceof ArrayAtom;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Operation\Type;

class IsStringOperation extends Type
{
    const IDENTIFIER = 'string?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return is_string($argument) || $argument instanceof StringAtom;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}
<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsIdentifierOperation extends StandardOperation
{
    const IDENTIFIER = 'identifier?';
    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof IdentifierAtom;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

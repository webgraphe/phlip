<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Operation\StandardOperation;

class IsStringOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = 'string?';

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

<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsIdentifierOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'identifier?';

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

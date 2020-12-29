<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsCallableOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'callable?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return is_callable($argument);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

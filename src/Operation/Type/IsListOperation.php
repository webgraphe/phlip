<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsListOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'list?';

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

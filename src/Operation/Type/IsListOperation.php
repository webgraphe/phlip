<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsListOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'list?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof ProperList;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

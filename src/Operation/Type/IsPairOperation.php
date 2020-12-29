<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormCollection\DottedPair;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsPairOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'pair?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof DottedPair;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

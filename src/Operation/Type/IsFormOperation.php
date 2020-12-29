<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsFormOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'form?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof FormContract;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

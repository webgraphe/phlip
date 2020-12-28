<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsMacroOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'macro?';

    public function __invoke(...$arguments): bool
    {
        return array_shift($arguments) instanceof Macro;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

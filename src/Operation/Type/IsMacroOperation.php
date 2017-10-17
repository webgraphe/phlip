<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\Operation\Type;

class IsMacroOperation extends Type
{
    const IDENTIFIER = 'macro?';
    /**
     * @param array ...$arguments
     * @return bool
     */
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

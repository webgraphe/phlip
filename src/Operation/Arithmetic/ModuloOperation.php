<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Operation\StandardOperation;

class ModuloOperation extends StandardOperation
{
    const IDENTIFIER = '%';
    const IDENTIFIER_ALTERNATIVE = 'mod';

    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        return $left % $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

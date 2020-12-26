<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Operation\StandardOperation;

class SubtractionOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = '-';
    /** @var string */
    const IDENTIFIER_ALTERNATIVE = 'sub';

    public function __invoke(...$arguments)
    {
        if (!$arguments) {
            return 0;
        }

        $first = array_shift($arguments);

        return $arguments ? $first - array_sum($arguments) : -$first;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}
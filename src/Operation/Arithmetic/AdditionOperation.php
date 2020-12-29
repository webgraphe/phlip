<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class AdditionOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = '+';
    /** @var string */
    const IDENTIFIER_ALTERNATIVE = 'add';

    public function __invoke(...$arguments)
    {
        return array_sum($arguments);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class AdditionOperation extends Operation implements FunctionContract
{
    const IDENTIFIER = '+';
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

<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class DivisionOperation extends Operation implements FunctionContract
{
    const IDENTIFIER = '/';
    const IDENTIFIER_ALTERNATIVE = 'div';

    public function __invoke(...$arguments)
    {
        $first = array_shift($arguments);

        return $arguments
            ? $first / MultiplicationOperation::product($arguments)
            : ($first ? 1 / $first : INF);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

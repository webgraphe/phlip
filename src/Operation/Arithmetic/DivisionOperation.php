<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class DivisionOperation extends AutomaticOperation
{
    const IDENTIFIER = '/';
    const IDENTIFIER_ALTERNATIVE = 'div';

    /**
     * @param array ...$arguments
     * @return float|int|mixed
     * @throws AssertionException
     */
    public function __invoke(...$arguments)
    {
        $first = array_shift($arguments);

        $numerator = $arguments ? $first : 1;
        $divisor = $arguments ? MultiplicationOperation::product(...$arguments) : $first;

        if (!$divisor) {
            throw new AssertionException('Division by zero');
        }

        return $numerator / $divisor;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

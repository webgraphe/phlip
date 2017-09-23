<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class LesserThanOperation extends Operation\Comparison
{
    const IDENTIFIER = '<';
    const IDENTIFIER_ALTERNATIVE= 'lt?';

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $left = self::assertNativeValue(array_shift($arguments));
        $right = self::assertNativeValue(array_shift($arguments));

        return $left < $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

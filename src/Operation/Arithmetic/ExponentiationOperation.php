<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class ExponentiationOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = '**';

    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        return $left ** $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

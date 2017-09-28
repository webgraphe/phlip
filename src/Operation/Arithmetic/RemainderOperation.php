<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class RemainderOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = 'rem';

    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        return fmod($left, $right);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

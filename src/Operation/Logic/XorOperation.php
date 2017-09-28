<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\Operation;

class XorOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = 'xor';

    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        return $left xor $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

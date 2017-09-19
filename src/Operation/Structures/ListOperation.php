<?php

namespace Webgraphe\Phlip\Operation\Structures;

use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class ListOperation extends Operation implements FunctionContract
{
    const IDENTIFIER = 'list';

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        return $arguments;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}
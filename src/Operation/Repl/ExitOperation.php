<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Webgraphe\Phlip\Operation\StandardOperation;

class ExitOperation extends StandardOperation
{
    const IDENTIFIER = 'exit';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        echo "Good bye!" . PHP_EOL . PHP_EOL;
        exit($arguments ? (int)$arguments[0] : 0);
    }
}

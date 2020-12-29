<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class ExitOperation extends AutomaticOperation
{
    /** @var string */
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
     * @codeCoverageIgnore
     */
    public function __invoke(...$arguments)
    {
        exit($arguments ? (int)$arguments[0] : 0);
    }
}

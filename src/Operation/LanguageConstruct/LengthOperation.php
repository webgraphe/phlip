<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class LengthOperation extends AutomaticOperation
{
    const IDENTIFIER = 'length';

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
    public function __invoke(...$arguments): int
    {
        $argument = isset($arguments[0]) ? $arguments[0] : null;
        if (null === $argument) {
            return 0;
        }

        if (is_array($argument)) {
            return count($argument);
        }

        if (is_object($argument)) {
            return count(get_object_vars($argument));
        }

        return mb_strlen((string)$argument);
    }
}

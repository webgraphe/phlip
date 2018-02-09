<?php

namespace Webgraphe\Phlip\Operation\String;

use Webgraphe\Phlip\Operation\StandardOperation;

class ImplodeOperation extends StandardOperation
{
    const IDENTIFIER = 'implode';
    const IDENTIFIER_ALTERNATIVE = 'join';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $glue = array_shift($arguments);
        return implode(
            $glue,
            array_merge(
                ...array_map(
                    function ($value) {
                        return is_array($value) ? $value : [$value];
                    },
                    $arguments
                )
            )
        );
    }
}

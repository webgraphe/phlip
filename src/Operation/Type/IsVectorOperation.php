<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormCollection\Vector;
use Webgraphe\Phlip\Operation\Type;

class IsVectorOperation extends Type
{
    const IDENTIFIER = 'vector?';

    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return self::isIndexedArray($argument) || $argument instanceof Vector;
    }

    /**
     * @param mixed $array
     * @return bool
     */
    public static function isIndexedArray($array): bool
    {
        if (!is_array($array)) {
            return false;
        }

        $keys = array_keys($array);

        return $keys === array_keys($keys);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

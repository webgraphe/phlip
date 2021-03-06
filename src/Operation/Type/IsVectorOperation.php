<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\FormCollection\Vector;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsVectorOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'vector?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return static::isIndexedArray($argument) || $argument instanceof Vector;
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

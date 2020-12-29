<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Operation\AutomaticOperation;

class MultiplicationOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = '*';
    /** @var string */
    const IDENTIFIER_ALTERNATIVE = 'mul';

    /**
     * @param int|float ...$arguments
     * @return number
     */
    public function __invoke(...$arguments)
    {
        return static::product(...$arguments);
    }

    /**
     * @param int|float ...$arguments
     * @return number
     */
    public static function product(...$arguments)
    {
        $product = 1;
        array_map(
            function ($argument) use (&$product) {
                $product *= $argument;
            },
            $arguments
        );

        return $product;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

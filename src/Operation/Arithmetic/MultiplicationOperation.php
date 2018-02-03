<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Operation\StandardOperation;

class MultiplicationOperation extends StandardOperation
{
    const IDENTIFIER = '*';
    const IDENTIFIER_ALTERNATIVE = 'mul';

    public function __invoke(...$arguments)
    {
        return self::product(...$arguments);
    }

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

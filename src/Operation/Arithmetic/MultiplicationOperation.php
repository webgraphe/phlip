<?php

namespace Webgraphe\Phlip\Operation\Arithmetic;

use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class MultiplicationOperation extends Operation implements FunctionContract
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
        array_walk($arguments, function ($argument) use (&$product) {
            $product *= $argument;
        });

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

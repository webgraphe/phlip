<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class EqualityOperation extends Operation implements FunctionContract
{
    const IDENTIFIER = 'equals?';

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        if ($left instanceof ExpressionContract && $right instanceof ExpressionContract) {
            return $left->equals($right);
        }

        return $left === $right;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}
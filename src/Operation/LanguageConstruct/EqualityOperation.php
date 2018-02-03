<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Operation\StandardOperation;

class EqualityOperation extends StandardOperation
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

        if ($left instanceof FormContract && $right instanceof FormContract) {
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
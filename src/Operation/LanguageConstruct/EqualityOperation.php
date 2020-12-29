<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class EqualityOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'equals?';

    /**
     * @param array ...$arguments
     * @return bool
     */
    public function __invoke(...$arguments): bool
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
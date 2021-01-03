<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class AtomOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'atom?';

    public function __invoke(...$arguments): bool
    {
        $variable = array_shift($arguments);

        return is_scalar($variable)
            || null === $variable
            || is_array($variable) && 0 === count($variable)
            || $variable instanceof Atom
            || $variable instanceof FormList && 0 === count($variable);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

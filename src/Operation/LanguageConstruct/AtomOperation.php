<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation;
use Webgraphe\Phlip\Operation\PrimaryFunction;
use Webgraphe\Phlip\QuotedExpression;

class AtomOperation extends Operation implements FunctionContract
{
    const IDENTIFIER = 'atom?';

    public function __invoke(...$arguments): bool
    {
        $variable = array_shift($arguments);

        return is_scalar($variable)
            || null === $variable
            || is_array($variable) && 0 === count($variable)
            || $variable instanceof Atom
            || $variable instanceof ExpressionList && 0 === count($variable);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

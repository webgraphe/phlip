<?php

namespace Webgraphe\Phlip\Operation\Type;

use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class IsKeywordOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'keyword?';

    public function __invoke(...$arguments): bool
    {
        $argument = array_shift($arguments);

        return $argument instanceof KeywordAtom;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

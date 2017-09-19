<?php

namespace Webgraphe\Phlip\Operation\Comparison;

use Webgraphe\Phlip\Atom\LiteralAtom;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\Operation;

class LesserThanOrEqualToOperation extends Operation implements FunctionContract
{
    const IDENTIFIER = '<=';
    const IDENTIFIER_ALTERNATIVE= 'lte?';

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $left = array_shift($arguments);
        $right = array_shift($arguments);

        if ($left instanceof LiteralAtom && $right instanceof LiteralAtom) {
            return $left->getValue() <= $right->getValue();
        }

        return $left->equals($right) ? true : null;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

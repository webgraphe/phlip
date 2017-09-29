<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class AndOperation extends PrimaryOperation
{
    const IDENTIFIER = 'and';
    const IDENTIFIER_ALTERNATIVE = '&&';

    protected function invoke(ContextContract $context, ProperList $expressions)
    {
        $result = null;
        while ($expression = $expressions->getHead()) {
            if (!($result = $expression->evaluate($context))) {
                return $result;
            }

            $expressions = $expressions->getTail();
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }
}

<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class OrOperation extends PrimaryOperation
{
    const IDENTIFIER = 'or';
    const IDENTIFIER_ALTERNATIVE = '||';

    protected function invoke(ContextContract $context, FormList $expressions)
    {
        $result = null;
        while ($expression = $expressions->getHead()) {
            if ($result = $expression->evaluate($context)) {
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

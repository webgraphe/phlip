<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class AndOperation extends PrimaryOperation
{
    const IDENTIFIER = 'and';
    const IDENTIFIER_ALTERNATIVE = '&&';

    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $result = null;
        while ($expression = $expressions->getHeadExpression()) {
            if (!($result = $expression->evaluate($context))) {
                return $result;
            }

            $expressions = $expressions->getTailExpressions();
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

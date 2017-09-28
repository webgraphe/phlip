<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class BeginOperation extends PrimaryOperation
{
    const IDENTIFIER = 'begin';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $result = null;
        while ($expression = $expressions->getHeadExpression()) {
            $expressions = $expressions->getTailExpressions();
            $result = $expression->evaluate($context);
        }

        return $result;
    }
}

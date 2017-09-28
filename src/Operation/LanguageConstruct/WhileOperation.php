<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class WhileOperation extends PrimaryOperation
{
    const IDENTIFIER = 'while';

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
        $condition = $expressions->assertHeadExpression();
        while ($condition->evaluate($context)) {
            $statements = $expressions->getTailExpressions();
            while ($statement = $statements->getHeadExpression()) {
                $statements = $statements->getTailExpressions();
                $statement->evaluate($context);
            }
        }

        return null;
    }
}
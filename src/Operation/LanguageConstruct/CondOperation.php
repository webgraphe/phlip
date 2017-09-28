<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CondOperation extends PrimaryOperation
{
    const IDENTIFIER = 'cond';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        while ($condition = $expressions->getHeadExpression()) {
            $expressions = $expressions->getTailExpressions();
            $condition = ExpressionList::assertStaticType($condition);
            if ($condition->assertHeadExpression()->evaluate($context)) {
                return $condition->getTailExpressions()->assertHeadExpression()->evaluate($context);
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

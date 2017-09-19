<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct\Logic;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct;

class IfOperation extends LanguageConstruct
{
    const IDENTIFIER = 'if';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $tail = $expressions->getTailExpressions();

        if ($expressions->assertHeadExpression()->evaluate($context)) {
            $then = $tail->assertHeadExpression();

            return $then->evaluate($context);
        }

        if ($else = $tail->getTailExpressions()->getHeadExpression()) {
            return $else->evaluate($context);
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

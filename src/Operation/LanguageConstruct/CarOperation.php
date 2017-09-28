<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CarOperation extends PrimaryOperation
{
    const IDENTIFIER = 'car';
    const IDENTIFIER_ALTERNATIVE = 'head';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        return ExpressionList::assertStaticType($expressions->assertHeadExpression()->evaluate($context))->getHeadExpression();
    }
}

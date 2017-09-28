<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryOperation;
use Webgraphe\Phlip\QuotedExpression;

class QuoteOperation extends PrimaryOperation
{
    const IDENTIFIER = 'quote';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        return $expressions->assertHeadExpression();
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}
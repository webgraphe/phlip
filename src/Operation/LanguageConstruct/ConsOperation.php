<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct;
use Webgraphe\Phlip\Traits\AssertsTypes;

class ConsOperation extends LanguageConstruct
{
    use AssertsTypes;

    const IDENTIFIER = 'cons';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        /** @var ExpressionContract $head */
        $head = $expressions->assertHeadExpression()->evaluate($context);
        $tail = ExpressionList::asList(
            self::assertType(
                ExpressionContract::class,
                $expressions->getTailExpressions()->assertHeadExpression()->evaluate($context)
            )
        );
        if (!($head instanceof ExpressionContract)) {
            ExpressionList::fromArray([$head]);
        }

        return new ExpressionList($head, ...$tail->all());
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

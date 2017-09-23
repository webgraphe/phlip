<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\FunctionContract;
use Webgraphe\Phlip\ExpressionBuilder;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation;
use Webgraphe\Phlip\Traits\AssertsTypes;

class ConsOperation extends Operation implements FunctionContract
{
    use AssertsTypes;

    const IDENTIFIER = 'cons';

    /** @var ExpressionBuilder */
    private $expressionBuilder;

    public function __construct(ExpressionBuilder $builder = null)
    {
        $this->expressionBuilder = $builder ?? new ExpressionBuilder;
    }

    /**
     * @param ExpressionContract[] ...$arguments
     * @return ExpressionList
     */
    public function __invoke(...$arguments): ExpressionList
    {
        $head = $this->expressionBuilder->asExpression(array_shift($arguments));
        $tail = $this->expressionBuilder->asExpression(array_shift($arguments));

        return new ExpressionList($head, ...ExpressionList::asList($tail)->all());
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

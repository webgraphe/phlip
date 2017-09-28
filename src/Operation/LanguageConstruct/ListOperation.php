<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\ExpressionBuilder;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation;

class ListOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = 'list';

    /** @var ExpressionBuilder */
    private $expressionBuilder;

    public function __construct(ExpressionBuilder $expressionBuilder = null)
    {
        $this->expressionBuilder = $expressionBuilder ?? new ExpressionBuilder;
    }

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        return new ExpressionList(
            ...array_map(
                function ($argument) {
                    return $this->expressionBuilder->asExpression($argument);
                },
                $arguments
            )
        );
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}
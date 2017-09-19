<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;

class QuotedExpression implements ExpressionContract
{
    /** @var ExpressionContract */
    private $expression;

    public function __construct(ExpressionContract $expression)
    {
        $this->expression = $expression;
    }

    /**
     * @param ContextContract $context
     * @return ExpressionContract
     */
    public function evaluate(ContextContract $context): ExpressionContract
    {
        return $this->expression;
    }

    public function __toString(): string
    {
        return "'" . (string)$this->expression;
    }

    public function equals(ExpressionContract $against): bool
    {
        return $against instanceof static && $this->expression->equals($against->expression);
    }

    /**
     * @return ExpressionContract
     */
    public function getExpression(): ExpressionContract
    {
        return $this->expression;
    }
}

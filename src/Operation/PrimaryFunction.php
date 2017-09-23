<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\LanguageConstructContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation;

abstract class PrimaryFunction extends Operation implements LanguageConstructContract
{
    /**
     * @param ContextContract $context
     * @param ExpressionContract[] $expressions
     * @return mixed
     */
    public final function __invoke(ContextContract $context, ExpressionContract ...$expressions)
    {
        return $this->invoke($context, new ExpressionList(...$expressions));
    }

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    abstract protected function invoke(ContextContract $context, ExpressionList $expressions);
}

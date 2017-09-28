<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\ExpressionList;

class CallablePrimaryOperationOperation implements PrimaryOperationContract
{
    /** @var callable */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param ContextContract $context
     * @param ExpressionContract[] $expressions
     * @return mixed
     */
    public function __invoke(ContextContract $context, ExpressionContract ...$expressions)
    {
        return call_user_func($this->callback, $context, new ExpressionList(...$expressions));
    }
}

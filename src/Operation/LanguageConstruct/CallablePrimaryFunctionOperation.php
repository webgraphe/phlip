<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use SebastianBergmann\GlobalState\RuntimeException;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryFunction;

/**
 * @todo Move to tests
 */
class CallablePrimaryFunctionOperation extends PrimaryFunction
{
    /** @var callable */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     * @throws \RuntimeException
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        return call_user_func($this->callback, $context, $expressions);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [];
    }
}

<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\FormCollection\ProperList;

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
     * @param FormContract[] $forms
     * @return mixed
     */
    public function __invoke(ContextContract $context, FormContract ...$forms)
    {
        return call_user_func($this->callback, $context, new ProperList(...$forms));
    }
}

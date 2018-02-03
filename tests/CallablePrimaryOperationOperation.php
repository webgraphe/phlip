<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
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

    /**
     * @param WalkerContract $walker
     * @param FormContract[] ...$forms
     * @return FormContract[]
     */
    public function walk(WalkerContract $walker, FormContract ...$forms): array
    {
        return array_map($walker, $forms);
    }
}

<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\ManualOperationContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation;

abstract class ManualOperation extends Operation implements ManualOperationContract
{
    /**
     * @param ContextContract $context
     * @param FormContract ...$forms
     * @return mixed
     */
    public final function __invoke(ContextContract $context, FormContract ...$forms)
    {
        return $this->invoke($context, new ProperList(...$forms));
    }

    /**
     * @param WalkerContract $walker
     * @param FormContract ...$forms
     * @return FormContract[]
     */
    public function walk(WalkerContract $walker, FormContract ...$forms): array
    {
        return array_map($walker, $forms);
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     */
    abstract protected function invoke(ContextContract $context, ProperList $forms);
}

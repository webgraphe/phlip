<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;
use Webgraphe\Phlip\Traits\AssertsClasses;

class InstanceOperation extends PrimaryOperation
{
    use AssertsClasses;

    public const IDENTIFIER = 'instance?';

    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return bool
     * @throws AssertionException
     * @throws ContextException
     */
    protected function invoke(ContextContract $context, ProperList $forms): bool
    {
        $thing = $forms->assertHead()->evaluate($context);
        $class = $this->assertClassExists($forms->getTail()->getHead());

        return $thing instanceof $class;
    }
}

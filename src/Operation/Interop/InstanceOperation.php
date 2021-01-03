<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;
use Webgraphe\Phlip\Traits\AssertsClasses;

class InstanceOperation extends ManualOperation
{
    use AssertsClasses;

    /** @var string */
    public const IDENTIFIER = 'instance?';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param FormList $forms
     * @return bool
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms): bool
    {
        $thing = $context->execute($forms->assertHead());
        $class = static::assertClassExists($forms->assertTailHead());

        return $thing instanceof $class;
    }
}

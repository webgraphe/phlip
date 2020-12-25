<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Traits\AssertsObjects;

class CloneOperation extends PhpInteroperableOperation
{
    use AssertsObjects;

    /** @var string */
    public const IDENTIFIER = 'clone';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return object
     * @throws AssertionException
     * @throws ContextException
     */
    protected function invoke(ContextContract $context, ProperList $forms): object
    {
        $original = static::assertObject($forms->assertHead()->evaluate($context));
        $this->assertPhpInteroperableContext($context, get_class($original));

        return clone $original;
    }
}

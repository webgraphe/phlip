<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\FormList;
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
     * @param FormList $forms
     * @return object
     * @throws AssertionException
     * @throws ContextException
     */
    protected function invoke(ContextContract $context, FormList $forms): object
    {
        $original = static::assertObject($context->execute($forms->assertHead()));
        $this->assertPhpInteroperableContext($context, get_class($original));

        return clone $original;
    }
}

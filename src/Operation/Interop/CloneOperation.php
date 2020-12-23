<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CloneOperation extends PrimaryOperation
{
    public const IDENTIFIER = 'clone';

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
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        return clone $this->assertObject($forms->assertHead()->evaluate($context));
    }

    /**
     * @param mixed $thing
     * @return object
     * @throws AssertionException
     */
    private function assertObject($thing): object
    {
        if (!is_object($thing)) {
            throw new AssertionException("Cannot clone a non-object");
        }

        return $thing;
    }
}

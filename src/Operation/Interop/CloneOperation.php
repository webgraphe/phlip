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
        $head = $forms->assertHead();
        $identifier = IdentifierAtom::assertStaticType($head);

        return clone $this->assertObject($context, $identifier->getValue());
    }

    /**
     * @param ContextContract $context
     * @param string $identifier
     * @return object|string
     * @throws AssertionException
     * @throws ContextException
     */
    private function assertObject(ContextContract $context, string $identifier)
    {
        if (!is_object($object = $context->get($identifier))) {
            throw new AssertionException("'{$identifier}' is not an object");
        }

        return $object;
    }
}

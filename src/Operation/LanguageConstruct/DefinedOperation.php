<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class DefinedOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'defined?';

    /**
     * @param ContextContract $context
     * @param FormList $forms
     * @return bool
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms): bool
    {
        $variable = IdentifierAtom::assertStaticType($forms->getHead());

        return $context->has($variable->getValue());
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

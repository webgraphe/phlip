<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class DefinedOperation extends PrimaryOperation
{
    const IDENTIFIER = 'defined?';

    protected function invoke(ContextContract $context, ProperList $expressions)
    {
        $variable = IdentifierAtom::assertStaticType($expressions->getHead());

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

<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class DefinedOperation extends PrimaryOperation
{
    const IDENTIFIER = 'defined?';

    protected function invoke(ContextContract $context, FormList $expressions)
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

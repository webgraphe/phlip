<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class DefinedOperation extends PrimaryOperation
{
    const IDENTIFIER = 'defined?';

    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $variable = IdentifierAtom::assertStaticType($expressions->getHeadExpression());

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

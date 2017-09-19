<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct;

class GetOperation extends LanguageConstruct
{
    const IDENTIFIER = 'get';

    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        return $context->get(IdentifierAtom::assertStaticType($expressions->getHeadExpression())->getValue());
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

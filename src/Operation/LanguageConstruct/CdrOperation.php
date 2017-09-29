<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CdrOperation extends PrimaryOperation
{
    const IDENTIFIER = 'cdr';
    const IDENTIFIER_ALTERNATIVE = 'tail';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ProperList $expressions)
    {
        return ProperList::assertStaticType($expressions->assertHead()->evaluate($context))->getTail();
    }
}

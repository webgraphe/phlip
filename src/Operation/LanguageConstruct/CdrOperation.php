<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormList;
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
     * @param FormList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, FormList $expressions)
    {
        return FormList::assertStaticType($expressions->assertHead()->evaluate($context))->getTail();
    }
}

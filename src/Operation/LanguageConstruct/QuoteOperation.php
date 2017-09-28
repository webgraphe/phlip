<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class QuoteOperation extends PrimaryOperation
{
    const IDENTIFIER = 'quote';

    /**
     * @param ContextContract $context
     * @param FormList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, FormList $expressions)
    {
        return $expressions->assertHead();
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}
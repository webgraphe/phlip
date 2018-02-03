<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class QuoteOperation extends PrimaryOperation
{
    const IDENTIFIER = 'quote';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return FormContract
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        return $forms->assertHead();
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}
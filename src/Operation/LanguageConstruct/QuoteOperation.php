<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\ManualOperation;

class QuoteOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'quote';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return FormContract
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms): FormContract
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

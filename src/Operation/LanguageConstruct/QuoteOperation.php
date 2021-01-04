<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class QuoteOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'quote';

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return FormContract
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms): FormContract
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

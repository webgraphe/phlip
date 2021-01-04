<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\Operation\ManualOperation;

class MacroExpandOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'macro-expand';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return FormContract
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms): FormContract
    {
        $statement = FormList::assertStaticType($scope->execute($forms->assertHead()));
        $macro = Macro::assertStaticType($scope->execute($statement->assertHead()));

        return $macro->expand($statement->getTail());
    }
}

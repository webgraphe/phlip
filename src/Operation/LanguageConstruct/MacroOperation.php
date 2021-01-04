<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\Operation\ManualOperation;

class MacroOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'macro';

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
     * @return Macro
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms): Macro
    {
        return new Macro(
            $scope,
            FormList::assertStaticType($forms->assertHead()),
            $forms->assertTailHead()
        );
    }
}

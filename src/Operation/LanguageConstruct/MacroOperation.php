<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
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
     * @param ContextContract $context
     * @param FormList $forms
     * @return Macro
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms): Macro
    {
        return new Macro(
            $context,
            FormList::assertStaticType($forms->assertHead()),
            $forms->assertTailHead()
        );
    }
}

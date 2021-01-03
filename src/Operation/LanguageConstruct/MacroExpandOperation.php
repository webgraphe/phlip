<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
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
     * @param ContextContract $context
     * @param FormList $forms
     * @return FormContract
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms): FormContract
    {
        $statement = FormList::assertStaticType($context->execute($forms->assertHead()));
        $macro = Macro::assertStaticType($context->execute($statement->assertHead()));

        return $macro->expand($statement->getTail());
    }
}

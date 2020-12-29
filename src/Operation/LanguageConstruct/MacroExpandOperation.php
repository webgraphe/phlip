<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
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

    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $statement = ProperList::assertStaticType($context->execute($forms->assertHead()));
        $macro = Macro::assertStaticType($context->execute($statement->assertHead()));

        return $macro->expand($statement->getTail());
    }
}

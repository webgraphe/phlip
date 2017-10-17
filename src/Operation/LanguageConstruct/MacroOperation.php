<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class MacroOperation extends PrimaryOperation
{
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
     * @param ProperList $forms
     * @return mixed
     */
    protected function invoke(ContextContract $context, ProperList $forms): Macro
    {
        return new Macro(
            $context,
            ProperList::assertStaticType($forms->assertHead()),
            $forms->getTail()->assertHead()
        );
    }
}

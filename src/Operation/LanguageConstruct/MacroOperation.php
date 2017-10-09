<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
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
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $head = $forms->assertHead();
        $tail = $forms->getTail();

        $context->define(
            IdentifierAtom::assertStaticType($head)->getValue(),
            new Macro(
                ProperList::assertStaticType($tail->assertHead()),
                $tail->getTail()->assertHead()
            )
        );

        return null;
    }
}

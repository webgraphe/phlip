<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\Operation\StandardOperation;

class MacroExpandOperation extends StandardOperation
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
     * @param array ...$arguments
     * @return FormContract
     * @throws AssertionException
     */
    public function __invoke(...$arguments): FormContract
    {
        $macro = Macro::assertStaticType(array_shift($arguments));

        return $macro->expand(ProperList::assertStaticType(array_shift($arguments)));
    }
}

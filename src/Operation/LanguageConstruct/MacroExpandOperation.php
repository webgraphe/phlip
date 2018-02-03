<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\Operation;

class MacroExpandOperation extends Operation implements StandardOperationContract
{
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
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    public function __invoke(...$arguments): FormContract
    {
        $macro = Macro::assertStaticType(array_shift($arguments));

        return $macro->expand(ProperList::assertStaticType(array_shift($arguments)));
    }
}

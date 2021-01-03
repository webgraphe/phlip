<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\DottedPair;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class CarOperation extends ManualOperation
{
    const IDENTIFIER = 'car';
    const IDENTIFIER_ALTERNATIVE = 'head';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }

    /**
     * @param ContextContract $context
     * @param FormList $forms
     * @return FormContract|null
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms): ?FormContract
    {
        $consCell = $context->execute($forms->assertHead());
        if ($consCell instanceof DottedPair) {
            return $consCell->getFirst();
        }

        return FormList::assertStaticType($consCell)->getHead();
    }
}

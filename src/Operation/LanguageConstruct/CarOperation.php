<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CarOperation extends PrimaryOperation
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
     * @param ProperList $forms
     * @return FormContract|null
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        return ProperList::assertStaticType($context->execute($forms->assertHead()))->getHead();
    }
}

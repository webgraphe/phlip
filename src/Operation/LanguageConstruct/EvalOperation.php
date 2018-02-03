<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation;

class EvalOperation extends Operation\PrimaryOperation
{
    const IDENTIFIER = 'eval';

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
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        return $context->execute(ProperList::assertStaticType($context->execute($forms->assertHead())));
    }
}

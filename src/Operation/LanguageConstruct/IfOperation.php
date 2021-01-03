<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class IfOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'if';

    /**
     * @param ContextContract $context
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms)
    {
        $tail = $forms->getTail();

        if ($context->execute($forms->assertHead())) {
            $then = $tail->assertHead();

            return $context->execute($then);
        }

        if ($else = $tail->getTailHead()) {
            return $context->execute($else);
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

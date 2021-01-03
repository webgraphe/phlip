<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class BeginOperation extends ManualOperation
{
    const IDENTIFIER = 'begin';

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
     * @return mixed
     */
    protected function invoke(ContextContract $context, FormList $forms)
    {
        $result = null;
        while ($form = $forms->getHead()) {
            $forms = $forms->getTail();
            $result = $context->execute($form);
        }

        return $result;
    }
}

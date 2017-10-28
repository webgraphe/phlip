<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class BeginOperation extends PrimaryOperation
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
     * @param ProperList $forms
     * @return mixed
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $result = null;
        while ($form = $forms->getHead()) {
            $forms = $forms->getTail();
            $result = $context->execute($form);
        }

        return $result;
    }
}

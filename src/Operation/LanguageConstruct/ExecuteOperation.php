<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class ExecuteOperation extends PrimaryOperation
{
    const IDENTIFIER = 'execute';

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

        $global = $context->global();
        while ($head = $forms->getHead()) {
            $forms = $forms->getTail();
            $result = $global->execute($context->execute($head));
        }

        return $result;
    }
}

<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\ContextAnchor;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class ContextAnchorOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'context-anchor';

    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param FormList $forms
     * @return mixed|void
     */
    protected function invoke(ContextContract $context, FormList $forms): ContextAnchor
    {
        $global = $context;
        while ($global->getParent()) {
            $global = $global->getParent();
        }

        return new ContextAnchor($global);
    }
}

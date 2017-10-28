<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class OrOperation extends PrimaryOperation
{
    const IDENTIFIER = 'or';

    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $result = null;
        while ($form = $forms->getHead()) {
            if ($result = $form->evaluate($context)) {
                return $result;
            }
            $forms = $forms->getTail();
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

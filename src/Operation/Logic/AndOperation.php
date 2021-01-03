<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class AndOperation extends ManualOperation
{
    const IDENTIFIER = 'and';

    protected function invoke(ContextContract $context, FormList $forms)
    {
        $result = null;
        while ($form = $forms->getHead()) {
            if (!($result = $context->execute($form))) {
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

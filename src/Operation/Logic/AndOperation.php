<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class AndOperation extends PrimaryOperation
{
    const IDENTIFIER = 'and';

    protected function invoke(ContextContract $context, ProperList $forms)
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

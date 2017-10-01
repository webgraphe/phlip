<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class IfOperation extends PrimaryOperation
{
    const IDENTIFIER = 'if';

    /**
     * @param ContextContract $context
     * @param ProperList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ProperList $expressions)
    {
        $tail = $expressions->getTail();

        if ($expressions->assertHead()->evaluate($context)) {
            $then = $tail->assertHead();

            return $then->evaluate($context);
        }

        if ($else = $tail->getTail()->getHead()) {
            return $else->evaluate($context);
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

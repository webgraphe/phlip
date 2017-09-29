<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ProperList;
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
     * @param ProperList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ProperList $expressions)
    {
        $result = null;
        while ($expression = $expressions->getHead()) {
            $expressions = $expressions->getTail();
            $result = $expression->evaluate($context);
        }

        return $result;
    }
}

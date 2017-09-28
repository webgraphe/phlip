<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class WhileOperation extends PrimaryOperation
{
    const IDENTIFIER = 'while';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param FormList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, FormList $expressions)
    {
        $condition = $expressions->assertHead();
        while ($condition->evaluate($context)) {
            $statements = $expressions->getTail();
            while ($statement = $statements->getHead()) {
                $statements = $statements->getTail();
                $statement->evaluate($context);
            }
        }

        return null;
    }
}
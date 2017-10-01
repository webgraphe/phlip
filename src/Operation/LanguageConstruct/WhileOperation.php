<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
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
     * @param ProperList $forms
     * @return mixed
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $condition = $forms->assertHead();
        while ($condition->evaluate($context)) {
            $statements = $forms->getTail();
            while ($statement = $statements->getHead()) {
                $statements = $statements->getTail();
                $statement->evaluate($context);
            }
        }

        return null;
    }
}
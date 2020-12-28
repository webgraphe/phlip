<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\ManualOperation;

class WhileOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'while';
    /** @var string */
    const IDENTIFIER_ALTERNATIVE = 'loop';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $condition = $forms->assertHead();
        while ($context->execute($condition)) {
            $statements = $forms->getTail();
            while ($statement = $statements->getHead()) {
                $statements = $statements->getTail();
                $context->execute($statement);
            }
        }

        return null;
    }
}

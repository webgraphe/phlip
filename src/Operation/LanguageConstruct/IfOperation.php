<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class IfOperation extends PrimaryOperation
{
    /** @var string */
    const IDENTIFIER = 'if';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $tail = $forms->getTail();

        if ($context->execute($forms->assertHead())) {
            $then = $tail->assertHead();

            return $context->execute($then);
        }

        if ($else = $tail->getTail()->getHead()) {
            return $context->execute($else);
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

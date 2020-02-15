<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CondOperation extends PrimaryOperation
{
    /** @var string */
    const IDENTIFIER = 'cond';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        while ($condition = $forms->getHead()) {
            $forms = $forms->getTail();
            $condition = ProperList::assertStaticType($condition);
            if ($context->execute($condition->assertHead())) {
                return $context->execute($condition->getTail()->assertHead());
            }
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

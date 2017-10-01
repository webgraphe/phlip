<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CondOperation extends PrimaryOperation
{
    const IDENTIFIER = 'cond';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        while ($condition = $forms->getHead()) {
            $forms = $forms->getTail();
            $condition = ProperList::assertStaticType($condition);
            if ($condition->assertHead()->evaluate($context)) {
                return $condition->getTail()->assertHead()->evaluate($context);
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

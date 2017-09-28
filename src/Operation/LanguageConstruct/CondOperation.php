<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class CondOperation extends PrimaryOperation
{
    const IDENTIFIER = 'cond';

    /**
     * @param ContextContract $context
     * @param FormList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, FormList $expressions)
    {
        while ($condition = $expressions->getHead()) {
            $expressions = $expressions->getTail();
            $condition = FormList::assertStaticType($condition);
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

<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class CondOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'cond';

    /**
     * @param ContextContract $context
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms)
    {
        while ($condition = $forms->getHead()) {
            $forms = $forms->getTail();
            $condition = FormList::assertStaticType($condition);
            if ($context->execute($condition->assertHead())) {
                return $context->execute($condition->assertTailHead());
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

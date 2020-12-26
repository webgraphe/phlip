<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection\DottedPair;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;
use Webgraphe\Phlip\Traits\AssertsTypes;

/**
 * Differs from the traditional cons. This operation un-shifts an element in a proper list.
 */
class ConsOperation extends PrimaryOperation
{
    use AssertsTypes;

    /** @var string */
    const IDENTIFIER = 'cons';

    /** @var FormBuilder */
    private $formBuilder;

    public function __construct(FormBuilder $builder = null)
    {
        $this->formBuilder = $builder ?? new FormBuilder();
    }

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
     * @return mixed|DottedPair|ProperList
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $head = $this->formBuilder->asForm($context, $context->execute($forms->assertHead()));
        $tail = $this->formBuilder->asForm($context, $context->execute($forms->getTail()->assertHead()));

        if ($tail instanceof ProperList) {
            return new ProperList($head, ...$tail->all());
        }

        return DottedPair::fromForms($head, $tail);
    }
}

<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection\DottedPair;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\AutomaticOperation;
use Webgraphe\Phlip\Traits\AssertsTypes;

/**
 * Differs from the traditional cons. This operation un-shifts an element in a proper list.
 */
class ConsOperation extends AutomaticOperation
{
    use AssertsTypes;

    /** @var string */
    const IDENTIFIER = 'cons';

    /** @var FormBuilder */
    private $formBuilder;

    public function __construct(FormBuilder $formBuilder = null)
    {
        $this->formBuilder = $formBuilder ?? new FormBuilder();
    }

    /**
     * @param FormContract ...$arguments
     * @return FormList|DottedPair|FormContract
     * @throws AssertionException
     */
    public function __invoke(...$arguments): FormContract
    {
        $head = $this->formBuilder->asForm(array_shift($arguments));
        $tail = $this->formBuilder->asForm(array_shift($arguments));

        if ($tail instanceof FormList) {
            return new FormList($head, ...$tail);
        }

        return DottedPair::fromForms($head, $tail);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

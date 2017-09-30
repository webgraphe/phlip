<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\Operation;
use Webgraphe\Phlip\Collection\ProperList;
use Webgraphe\Phlip\Traits\AssertsTypes;

/**
 * Differs from the traditional cons. This operation un-shifts an element in a proper list.
 */
class ConsOperation extends Operation implements StandardOperationContract
{
    use AssertsTypes;

    const IDENTIFIER = 'cons';

    /** @var FormBuilder */
    private $formBuilder;

    public function __construct(FormBuilder $formBuilder = null)
    {
        $this->formBuilder = $formBuilder ?? new FormBuilder;
    }

    /**
     * @param FormContract[] ...$arguments
     * @return FormContract
     */
    public function __invoke(...$arguments): FormContract
    {
        $head = $this->formBuilder->asForm(array_shift($arguments));
        $tail = $this->formBuilder->asForm(array_shift($arguments));

        return new ProperList($head, ...ProperList::asList($tail)->all());
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

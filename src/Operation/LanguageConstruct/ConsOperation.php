<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation;
use Webgraphe\Phlip\Traits\AssertsTypes;

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
     * @return FormList
     */
    public function __invoke(...$arguments): FormList
    {
        $head = $this->formBuilder->asForm(array_shift($arguments));
        $tail = $this->formBuilder->asForm(array_shift($arguments));

        return new FormList($head, ...FormList::asList($tail)->all());
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

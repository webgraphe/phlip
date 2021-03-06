<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\AutomaticOperation;

class ListOperation extends AutomaticOperation
{
    /** @var string */
    const IDENTIFIER = 'list';

    /** @var FormBuilder */
    private $formBuilder;

    public function __construct(FormBuilder $formBuilder = null)
    {
        $this->formBuilder = $formBuilder ?? new FormBuilder();
    }

    /**
     * @param array ...$arguments
     * @return FormList
     * @throws AssertionException
     */
    public function __invoke(...$arguments): FormList
    {
        return new FormList(
            ...array_map(
                function ($argument) {
                    return $this->formBuilder->asForm($argument);
                },
                $arguments
            )
        );
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

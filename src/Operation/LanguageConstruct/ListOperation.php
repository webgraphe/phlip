<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\StandardOperation;

class ListOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = 'list';

    private FormBuilder $formBuilder;

    public function __construct(FormBuilder $formBuilder = null)
    {
        $this->formBuilder = $formBuilder ?? new FormBuilder;
    }

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        return new ProperList(
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
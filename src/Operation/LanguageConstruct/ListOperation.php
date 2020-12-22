<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\StandardOperation;

class ListOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = 'list';

    /** @var FormBuilder */
    private $formBuilder;

    public function __construct(FormBuilder $formBuilder = null)
    {
        $this->formBuilder = $formBuilder ?? new FormBuilder;
    }

    /**
     * @param array ...$arguments
     * @return ProperList
     * @throws AssertionException
     */
    public function __invoke(...$arguments): ProperList
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

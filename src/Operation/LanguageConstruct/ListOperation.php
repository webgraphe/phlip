<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\StandardOperationContract;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\ProperList;
use Webgraphe\Phlip\Operation;

class ListOperation extends Operation implements StandardOperationContract
{
    const IDENTIFIER = 'list';

    /** @var FormBuilder */
    private $formBuilder;

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
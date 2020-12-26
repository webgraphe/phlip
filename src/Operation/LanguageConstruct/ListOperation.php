<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class ListOperation extends PrimaryOperation
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
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return ProperList
     */
    protected function invoke(ContextContract $context, ProperList $forms): ProperList
    {
        return $forms->map(
            function (FormContract $form) use ($context) {
                return $this->formBuilder->asForm($context, $context->execute($form));
            }
        );
    }
}

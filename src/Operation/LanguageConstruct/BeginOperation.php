<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class BeginOperation extends ManualOperation
{
    const IDENTIFIER = 'begin';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $result = null;
        while ($form = $forms->getHead()) {
            $forms = $forms->getTail();
            $result = $scope->execute($form);
        }

        return $result;
    }
}

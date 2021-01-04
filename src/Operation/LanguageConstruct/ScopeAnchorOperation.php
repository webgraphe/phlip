<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\ScopeAnchor;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class ScopeAnchorOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'scope-anchor';

    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed|void
     */
    protected function invoke(ScopeContract $scope, FormList $forms): ScopeAnchor
    {
        $global = $scope;
        while ($global->getParent()) {
            $global = $global->getParent();
        }

        return new ScopeAnchor($global);
    }
}

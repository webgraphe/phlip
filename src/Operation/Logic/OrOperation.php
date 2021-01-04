<?php

namespace Webgraphe\Phlip\Operation\Logic;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class OrOperation extends ManualOperation
{
    const IDENTIFIER = 'or';

    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $result = null;
        while ($form = $forms->getHead()) {
            if ($result = $scope->execute($form)) {
                return $result;
            }

            $forms = $forms->getTail();
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

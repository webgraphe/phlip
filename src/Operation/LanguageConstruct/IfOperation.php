<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class IfOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'if';

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $tail = $forms->getTail();

        if ($scope->execute($forms->assertHead())) {
            $then = $tail->assertHead();

            return $scope->execute($then);
        }

        if ($else = $tail->getTailHead()) {
            return $scope->execute($else);
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

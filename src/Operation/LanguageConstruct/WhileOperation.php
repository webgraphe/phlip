<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class WhileOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'while';
    /** @var string */
    const IDENTIFIER_ALTERNATIVE = 'loop';

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER, self::IDENTIFIER_ALTERNATIVE];
    }

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $condition = $forms->assertHead();
        while ($scope->execute($condition)) {
            $statements = $forms->getTail();
            while ($statement = $statements->getHead()) {
                $statements = $statements->getTail();
                $scope->execute($statement);
            }
        }

        return null;
    }
}

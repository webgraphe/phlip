<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class CondOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'cond';

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        while ($condition = $forms->getHead()) {
            $forms = $forms->getTail();
            $condition = FormList::assertStaticType($condition);
            if ($scope->execute($condition->assertHead())) {
                return $scope->execute($condition->assertTailHead());
            }
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

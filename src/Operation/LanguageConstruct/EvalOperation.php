<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\ScopeAnchor;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

/**
 * Evaluates a given expression in the bound scope of the operation.
 *
 * Because it's lexically scoped, the given expression cannot be evaluated in the local scope.
 */
class EvalOperation extends ManualOperation
{
    const IDENTIFIER = 'eval';

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
     * @throws ScopeException
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $result = null;

        $env = ($tailHead = $forms->getTailHead())
            ? $this->assertScopeAnchor($scope->execute($tailHead))->getScope()
            : $this->assertBoundedScope();
        $result = $env->execute($this->assertForm($scope->execute($forms->assertHead())));

        return $result;
    }

    /**
     * @param mixed $thing
     * @return FormContract
     * @throws AssertionException
     */
    private function assertForm($thing): FormContract
    {
        if ($thing instanceof FormContract) {
            return $thing;
        }

        $type = is_object($thing) ? get_class($thing) : gettype($thing);

        throw new AssertionException("Can't evaluate; not a form: ($type) {$thing}");
    }

    /**
     * @param mixed $thing
     * @return ScopeAnchor
     * @throws AssertionException
     */
    private function assertScopeAnchor($thing): ScopeAnchor
    {
        if ($thing instanceof ScopeAnchor) {
            return $thing;
        }

        throw new AssertionException("Not a scope anchor");
    }
}

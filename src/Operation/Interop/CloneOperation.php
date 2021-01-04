<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Traits\AssertsObjects;

class CloneOperation extends PhpInteroperableOperation
{
    use AssertsObjects;

    /** @var string */
    public const IDENTIFIER = 'clone';

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
     * @return object
     * @throws AssertionException
     * @throws ScopeException
     */
    protected function invoke(ScopeContract $scope, FormList $forms): object
    {
        $original = static::assertObject($scope->execute($forms->assertHead()));
        $this->assertPhpInteroperableScope($scope, get_class($original));

        return clone $original;
    }
}

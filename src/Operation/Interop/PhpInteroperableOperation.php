<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\PhpClassInteroperableContract;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Operation\ManualOperation;

abstract class PhpInteroperableOperation extends ManualOperation
{
    /**
     * @param ScopeContract $scope
     * @param string $class
     * @return ScopeContract|PhpClassInteroperableContract
     * @throws ScopeException
     */
    protected function assertPhpInteroperableScope(ScopeContract $scope, string $class): ScopeContract
    {
        if ($scope instanceof PhpClassInteroperableContract) {
            return $scope;
        }

        throw new ScopeException("Class '{$class}' requires an PHP interoperable scope");
    }
}

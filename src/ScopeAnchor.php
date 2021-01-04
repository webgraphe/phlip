<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ScopeContract;

class ScopeAnchor
{
    /** @var ScopeContract */
    private $scope;

    public function __construct(ScopeContract $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return ScopeContract
     */
    public function getScope(): ScopeContract
    {
        return $this->scope;
    }
}

<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\OperationContract;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Traits\AssertsStaticType;

abstract class Operation implements OperationContract
{
    use AssertsStaticType;

    /** @var ScopeContract|null */
    private $boundedScope;

    /**
     * @return string[]
     */
    abstract public function getIdentifiers(): array;

    /**
     * @param ScopeContract|null $scope
     * @return static
     */
    public function bindToScope(?ScopeContract $scope): self
    {
        $this->boundedScope = $scope;

        return $this;
    }

    /**
     * @return ScopeContract|null
     * @throws ScopeException
     */
    protected function assertBoundedScope(): ?ScopeContract
    {
        if ($this->boundedScope) {
            return $this->boundedScope;
        }

        $class = get_class($this);

        throw new ScopeException("{$class} is not bounded to a scope");
    }
}

<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\OperationContract;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Traits\AssertsStaticType;

abstract class Operation implements OperationContract
{
    use AssertsStaticType;

    /** @var ContextContract|null */
    private $boundedContext;

    /**
     * @return string[]
     */
    abstract public function getIdentifiers(): array;

    /**
     * @param ContextContract|null $context
     * @return static
     */
    public function bindToContext(?ContextContract $context): self
    {
        $this->boundedContext = $context;

        return $this;
    }

    /**
     * @return ContextContract|null
     * @throws ContextException
     */
    protected function assertBoundedContext(): ?ContextContract
    {
        if ($this->boundedContext) {
            return $this->boundedContext;
        }

        $class = get_class($this);

        throw new ContextException("{$class} is not bounded to a context");
    }
}

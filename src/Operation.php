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
     * @param ContextContract $context
     * @return static
     * @throws ContextException
     */
    protected function withBoundedContext(ContextContract $context): self
    {
        if ($this->isBounded()) {
            if (!$this->isBoundedTo($context)) {
                $class = get_class($this);
                throw new ContextException("{$class} instance is already bound to another context");
            }
        } else {
            $this->boundedContext = $context;
        }

        return $this;
    }

    public function isBounded(): bool
    {
        return (bool)$this->boundedContext;
    }

    public function isBoundedTo(ContextContract $context): bool
    {
        return $this->boundedContext === $context;
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

        throw new ContextException("{$class} is not bound to a context");
    }
}

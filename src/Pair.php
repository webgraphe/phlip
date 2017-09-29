<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class Pair implements FormContract, \Countable
{
    use AssertsStaticType;

    /** @var FormContract */
    private $first;
    /** @var FormContract */
    private $second;

    public function __construct(FormContract $first, FormContract $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    public function count(): int
    {
        return 2;
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function evaluate(ContextContract $context)
    {
        // TODO: Implement evaluate() method.
    }

    public function equals(FormContract $against): bool
    {
        // TODO: Implement equals() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}

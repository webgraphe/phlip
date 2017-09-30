<?php

namespace Webgraphe\Phlip\Collection;

use Webgraphe\Phlip\Collection;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class Pair extends Collection
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
     * @return array
     */
    public function evaluate(ContextContract $context): array
    {
        return [ $this->first->evaluate($context), $this->second->evaluate($context) ];
    }

    /**
     * @return FormContract
     */
    public function getFirst(): FormContract
    {
        return $this->first;
    }

    /**
     * @return FormContract
     */
    public function getSecond(): FormContract
    {
        return $this->second;
    }

    public function getOpeningSymbol(): Opening
    {
        return Opening\OpenPairSymbol::instance();
    }

    public function getClosingSymbol(): Closing
    {
        return Closing\ClosePairSymbol::instance();
    }

    /**
     * @return FormContract[]
     */
    public function all(): array
    {
        return [$this->first, $this->second];
    }
}

<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\Contracts\FormCollectionContract;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\DotSymbol;
use Webgraphe\Phlip\Symbol\Opening;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class DottedPair extends FormCollection
{
    use AssertsStaticType;

    /** @var FormContract */
    private $first;
    /** @var FormContract */
    private $second;

    final protected function __construct(FormContract $first, FormContract $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @param FormContract $left
     * @param FormContract $right
     * @param FormContract ...$others
     * @return DottedPair
     * @throws AssertionException
     */
    public static function fromForms(FormContract $left, FormContract $right, FormContract ...$others): DottedPair
    {
        if ($others) {
            return new static($left, static::fromForms($right, ...$others));
        }

        if ($right instanceof ProperList) {
            throw new AssertionException("Unexpected proper list");
        }

        return new static($left, $right);
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
        return [ $context->execute($this->first), $context->execute($this->second) ];
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

    public function __toString(): string
    {
        $elements = [];
        $elements[] = (string)$this->first;
        $second = $this->second;
        while ($second instanceof static) {
            $elements[] = $second->first;
            $second = $second->second;
        }
        $elements[] = DotSymbol::CHARACTER;
        $elements[] = (string)$second;

        return $this->getOpeningSymbol()->getValue()
            . implode(' ', $elements)
            . $this->getClosingSymbol()->getValue();
    }

    public function getOpeningSymbol(): Opening
    {
        return Opening\OpenListSymbol::instance();
    }

    public function getClosingSymbol(): Closing
    {
        return Closing\CloseListSymbol::instance();
    }

    /**
     * @return FormContract[]
     */
    public function all(): array
    {
        return [$this->first, $this->second];
    }

    /**
     * @param callable $callback
     * @return FormCollectionContract|static
     */
    public function map(callable $callback): FormCollectionContract
    {
        return new static(...array_map($callback, $this->all()));
    }
}

<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class Map extends FormCollection
{
    /** @var ProperList[] */
    private $pairs = [];

    /**
     * Map constructor.
     * @param ProperList ...$pairs
     * @throws \Webgraphe\Phlip\Exception\AssertionException
     */
    final public function __construct(ProperList ...$pairs)
    {
        foreach ($pairs as $pair) {
            if (2 !== ($count = $pair->count())) {
                throw new AssertionException("Expected a proper list of 2 forms; got $count");
            }

            $this->pairs[] = $pair;
        }
    }

    /**
     * @param ContextContract $context
     * @return \stdClass
     */
    public function evaluate(ContextContract $context): \stdClass
    {
        $map = (object)[];
        foreach ($this->pairs as $pair) {
            $map->{$context->execute($pair->getHead())} = $context->execute($pair->getTail()->getHead());
        }

        return $map;
    }

    public function count(): int
    {
        return count($this->pairs);
    }

    public function getOpeningSymbol(): Opening
    {
        return Opening\OpenMapSymbol::instance();
    }

    public function getClosingSymbol(): Closing
    {
        return Closing\CloseMapSymbol::instance();
    }

    /**
     * @return ProperList[]
     */
    public function all(): array
    {
        return $this->pairs;
    }

    /**
     * @param callable $callback
     * @return FormCollection|static
     * @throws AssertionException
     */
    public function map(callable $callback): FormCollection
    {
        return new static(...array_map($callback, $this->all()));
    }

    /**
     * @param FormContract $form
     * @return string
     * @throws AssertionException
     */
    protected function stringifyFormItem(FormContract $form): string
    {
        $list = ProperList::assertStaticType($form);

        return (string)$list->getHead() . ' ' . (string)$list->getTail()->getHead();
    }
}

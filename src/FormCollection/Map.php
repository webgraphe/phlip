<?php

namespace Webgraphe\Phlip\FormCollection;

use stdClass;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormCollectionContract;
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
     * @param ProperList ...$pairs
     * @throws AssertionException
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
     * @param mixed $key
     * @return string|integer
     * @throws AssertionException
     */
    private static function assertScalarOrNull($key)
    {
        if (!is_scalar($key) && null !== $key) {
            throw new AssertionException('Key is not a scalar');
        }

        return $key;
    }

    /**
     * @param ContextContract $context
     * @return stdClass
     * @throws AssertionException
     */
    public function evaluate(ContextContract $context): stdClass
    {
        $map = (object)[];
        foreach ($this->pairs as $pair) {
            $key = $context->execute($pair->getHead());
            $map->{self::assertScalarOrNull($key)} = $context->execute($pair->getTail()->getHead());
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
     * @return FormCollectionContract|static
     * @throws AssertionException
     */
    public function map(callable $callback): FormCollectionContract
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

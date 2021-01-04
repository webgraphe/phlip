<?php

namespace Webgraphe\Phlip\FormCollection;

use stdClass;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormCollectionContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class Map extends FormCollection
{
    /** @var FormList[] */
    private $pairs = [];

    /**
     * @param FormList ...$pairs
     * @throws AssertionException
     */
    final public function __construct(FormList ...$pairs)
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
    protected static function assertScalarOrNull($key)
    {
        if (!is_scalar($key) && null !== $key) {
            throw new AssertionException('Key is not a scalar');
        }

        return $key;
    }

    /**
     * @param ScopeContract $scope
     * @return stdClass
     * @throws AssertionException
     */
    public function evaluate(ScopeContract $scope): stdClass
    {
        $map = (object)[];
        foreach ($this->pairs as $pair) {
            $key = $scope->execute($pair->assertHead());
            $map->{static::assertScalarOrNull($key)} = $scope->execute($pair->assertTailHead());
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
     * @return FormList[]
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
        $list = FormList::assertStaticType($form);

        return (string)$list->assertHead() . ' ' . (string)$list->assertTailHead();
    }
}

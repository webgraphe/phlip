<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class Map extends FormCollection
{
    /** @var ProperList[] */
    private $pairs = [];

    public function __construct(ProperList ...$pairs)
    {
        foreach ($pairs as $pair) {
            $this->pairs[Atom::assertStaticType($pair->assertHead())->getValue()] = $pair->getTail()->assertHead();
        }
    }

    /**
     * @param ContextContract $context
     * @return \stdClass
     */
    public function evaluate(ContextContract $context): \stdClass
    {
        $map = (object)[];
        foreach ($this->pairs as $key => $value) {
            $map->{$key} = $value->evaluate($context);
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
     */
    public function map(callable $callback): FormCollection
    {
        return new static(...array_map($callback, $this->all()));
    }
}

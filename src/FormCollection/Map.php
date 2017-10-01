<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class Map extends FormCollection
{
    /** @var Pair[] */
    private $pairs = [];

    public function __construct(Pair ...$pairs)
    {
        foreach ($pairs as $pair) {
            $key = Atom::assertStaticType($pair->getFirst());
            $this->pairs[$key->getValue()] = $pair;
        }
    }

    /**
     * @param ContextContract $context
     * @return \stdClass
     */
    public function evaluate(ContextContract $context): \stdClass
    {
        $map = (object)[];
        foreach ($this as $key => $value) {
            /** @var $value Pair */
            $map->{$key} = $value->getSecond()->evaluate($context);
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
     * @return Pair[]
     */
    public function all(): array
    {
        return $this->pairs;
    }
}

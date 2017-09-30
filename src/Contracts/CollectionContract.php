<?php

namespace Webgraphe\Phlip\Contracts;

use Webgraphe\Phlip\CollectionIterator;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

interface CollectionContract extends FormContract, \Countable, \IteratorAggregate
{
    public function getOpeningSymbol(): Opening;

    public function getClosingSymbol(): Closing;

    public function getIterator(): CollectionIterator;

    public function count(): int;

    /**
     * @return FormContract[]
     */
    public function all(): array;
}

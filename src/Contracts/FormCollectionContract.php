<?php

namespace Webgraphe\Phlip\Contracts;

use Webgraphe\Phlip\FormCollectionIterator;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

interface FormCollectionContract extends FormContract, \Countable, \IteratorAggregate
{
    public function getOpeningSymbol(): Opening;

    public function getClosingSymbol(): Closing;

    public function getIterator(): FormCollectionIteratorContract;

    public function count(): int;

    /**
     * @return FormContract[]
     */
    public function all(): array;
}

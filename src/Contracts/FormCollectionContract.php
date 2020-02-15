<?php

namespace Webgraphe\Phlip\Contracts;

use Countable;
use IteratorAggregate;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

/**
 * Immutable collection of forms.
 */
interface FormCollectionContract extends FormContract, Countable, IteratorAggregate
{
    public function getOpeningSymbol(): Opening;

    public function getClosingSymbol(): Closing;

    public function getIterator(): FormCollectionIteratorContract;

    public function count(): int;

    /**
     * @return FormContract[]
     */
    public function all(): array;

    public function map(callable $callback): FormCollectionContract;
}

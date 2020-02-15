<?php

namespace Webgraphe\Phlip\Contracts;

use Iterator;

interface FormCollectionIteratorContract extends Iterator
{
    public function current(): ?FormContract;

    /**
     * @return void
     */
    public function next();

    /**
     * @return mixed A scalar on success, or null on failure.
     */
    public function key();

    public function valid(): bool;

    /**
     * @return void
     */
    public function rewind();
}

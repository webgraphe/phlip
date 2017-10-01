<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\FormCollectionContract;
use Webgraphe\Phlip\Contracts\FormCollectionIteratorContract;
use Webgraphe\Phlip\Contracts\FormContract;

class FormCollectionIterator implements FormCollectionIteratorContract
{
    /** @var FormContract[] */
    private $elements = [];
    /** @var int[]|string[] */
    private $keys = [];
    private $offset = 0;
    private $size = 0;

    public function __construct(FormCollectionContract $collection)
    {
        $this->elements = $collection->all();
        $this->keys = array_keys($this->elements);
        $this->size = count($collection);
    }

    public function current(): ?FormContract
    {
        return $this->valid() ? $this->elements[$this->keys[$this->offset]] : null;
    }

    public function next()
    {
        ++$this->offset;
    }

    /**
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->valid() ? $this->keys[$this->offset] : null;
    }

    public function valid(): bool
    {
        return $this->offset < $this->size;
    }

    public function rewind()
    {
        $this->offset = 0;
    }
}

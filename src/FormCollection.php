<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormCollectionContract;
use Webgraphe\Phlip\Contracts\FormCollectionIteratorContract;
use Webgraphe\Phlip\Contracts\FormContract;

abstract class FormCollection implements FormCollectionContract
{
    public function equals(FormContract $against): bool
    {
        $count = count($this);
        if (!($against instanceof static) || count($against) !== $count) {
            return false;
        }

        $iterator = $this->getIterator();
        return $count === iterator_apply(
            $iterator,
            function (FormCollectionIterator $self, FormCollectionIterator $other) {
                $result = $self->current()->equals($other->current());
                $other->next();

                return $result;
            },
            [ $iterator, $against->getIterator() ]
        );
    }

    /**
     * @param callable $callback
     * @return FormCollection|static
     */
    abstract public function map(callable $callback): FormCollection;

    public function getIterator(): FormCollectionIteratorContract
    {
        return new FormCollectionIterator($this);
    }

    public function getCodeAnchor(): ?CodeAnchorContract
    {
        $iterator = $this->getIterator();

        return $iterator->valid() ? $iterator->current()->getCodeAnchor() : null;
    }

    protected function stringifyFormItem(FormContract $form): string
    {
        return (string)$form;
    }

    protected function getFormItemDelimiter(): string
    {
        return ' ';
    }

    public function __toString(): string
    {
        $formsAsString = [];
        foreach ($this as $form) {
            $formsAsString[] = $this->stringifyFormItem($form);
        }

        return $this->getOpeningSymbol()->getValue()
            . implode($this->getFormItemDelimiter(), $formsAsString)
            . $this->getClosingSymbol()->getValue();
    }
}

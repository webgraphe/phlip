<?php

namespace Webgraphe\Phlip;

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

    public function getIterator(): FormCollectionIteratorContract
    {
        return new FormCollectionIterator($this);
    }

    public function __toString(): string
    {
        $formsAsString = [];
        foreach ($this as $key => $form) {
            $formsAsString[] = (string)$form;
        }

        return $this->getOpeningSymbol()->getValue()
            . implode(' ', $formsAsString)
            . $this->getClosingSymbol()->getValue();
    }
}

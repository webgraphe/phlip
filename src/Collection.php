<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CollectionContract;
use Webgraphe\Phlip\Contracts\FormContract;

abstract class Collection implements CollectionContract
{
    public function equals(FormContract $against): bool
    {
        $expressionCount = count($this);
        if (!($against instanceof static) || count($against) !== $expressionCount) {
            return false;
        }

        $iterator = $this->getIterator();
        return $expressionCount === iterator_apply(
            $iterator,
            function (CollectionIterator $self, CollectionIterator $other) {
                $result = $self->current()->equals($other->current());
                $other->next();

                return $result;
            },
            [ $iterator, $against->getIterator() ]
        );
    }

    public function getIterator(): CollectionIterator
    {
        return new CollectionIterator($this);
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

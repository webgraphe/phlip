<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\Contracts\FormCollectionContract;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class Vector extends FormCollection
{
    /** @var FormContract[] */
    private $elements;

    final public function __construct(FormContract ...$elements)
    {
        $this->elements = $elements;
    }

    /**
     * @param ScopeContract $scope
     * @return array
     */
    public function evaluate(ScopeContract $scope): array
    {
        return array_map(
            function (FormContract $form) use ($scope) {
                return $scope->execute($form);
            },
            $this->elements
        );
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function getOpeningSymbol(): Opening
    {
        return Opening\OpenVectorSymbol::instance();
    }

    public function getClosingSymbol(): Closing
    {
        return Closing\CloseVectorSymbol::instance();
    }

    /**
     * @return FormContract[]
     */
    public function all(): array
    {
        return $this->elements;
    }

    /**
     * @param callable $callback
     * @return FormCollectionContract|static
     */
    public function map(callable $callback): FormCollectionContract
    {
        return new static(...array_map($callback, $this->all()));
    }
}

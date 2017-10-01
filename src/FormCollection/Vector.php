<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class Vector extends FormCollection
{
    /** @var FormContract[] */
    private $elements;

    public function __construct(FormContract ...$elements)
    {
        $this->elements = $elements;
    }

    /**
     * @param ContextContract $context
     * @return array
     */
    public function evaluate(ContextContract $context): array
    {
        return array_map(
            function (FormContract $expression) use ($context) {
                return $expression->evaluate($context);
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
}

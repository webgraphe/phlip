<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\ArrayAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;

class FormBuilder
{
    /**
     * Normalizes something into an expression.
     * - Attempts to recreate expressions depending on the type.
     * - If it's already an expression, returns the expression itself.
     *
     * @param mixed $thing
     * @return FormContract
     * @throws AssertionException If the type of the input data could not be handled.
     */
    public function asForm($thing): FormContract
    {
        static $true, $false, $null;

        switch (true) {
            case $thing instanceof FormContract:
                return $thing;
            case null === $thing:
                return $null ?? ($null = new ProperList);
            case true === $thing:
                return $true ?? ($true = KeywordAtom::fromString('true'));
            case false === $thing:
                return $false ?? ($false = new ProperList);
            case is_string($thing):
                return new StringAtom($thing);
            case is_numeric($thing):
                return new NumberAtom($thing);
            case is_array($thing):
                return new ArrayAtom(
                    ...array_map(
                        function ($element) {
                            return $this->asForm($element);
                        },
                        $thing
                    )
                );
            default:
                $type = is_object($thing) ? get_class($thing) : gettype($thing);
                throw new AssertionException("Unhandled '$type'");
        }
    }
}

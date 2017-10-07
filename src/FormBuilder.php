<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\FormCollection\Pair;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\FormCollection\Vector;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;

class FormBuilder
{
    /**
     * Normalizes native constructions into forms.
     * - Recreates forms from native value types.
     * - Existing forms are returned as-is.
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
                return StringAtom::fromString($thing);
            case is_numeric($thing):
                return NumberAtom::fromString($thing);
            case is_array($thing):
                return new Vector(
                    ...array_map(
                        function ($element) {
                            return $this->asForm($element);
                        },
                        $thing
                    )
                );
            case is_object($thing):
                $properties = get_object_vars($thing);
                return new Map(
                    ...array_map(
                        function ($key, $value) {
                            return new Pair($this->asForm($key), $this->asForm($value));
                        },
                        array_keys($properties),
                        array_values($properties)
                    )
                );
            default:
                $type = is_object($thing) ? get_class($thing) : gettype($thing);
                throw new AssertionException("Unhandled '$type'");
        }
    }
}

<?php

namespace Webgraphe\Phlip;

use stdClass;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\FormCollection\Vector;

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
        if ($thing instanceof FormContract) {
            return $thing;
        }

        if (null === $thing || false === $thing) {
            return new ProperList;
        }

        if (true === $thing) {
            return KeywordAtom::fromString('true');
        }

        if (is_string($thing)) {
            return StringAtom::fromString($thing);
        }

        if (is_numeric($thing)) {
            return NumberAtom::fromString($thing);
        }

        if (is_array($thing)) {
            return new Vector(
                ...array_map(
                    function ($element) {
                        return $this->asForm($element);
                    },
                    $thing
                )
            );
        }

        if ($thing instanceof stdClass) {
            $properties = get_object_vars($thing);
            return new Map(
                ...array_map(
                    function ($key, $value) {
                        return new ProperList($this->asForm($key), $this->asForm($value));
                    },
                    array_keys($properties),
                    array_values($properties)
                )
            );
        }

        $type = is_object($thing) ? get_class($thing) : gettype($thing);
        throw new AssertionException("Unhandled '$type'");
    }
}

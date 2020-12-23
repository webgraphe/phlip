<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;

class StringAtom extends Atom
{
    /** @var string It is not possible to support the single quote as it would conflict with quoting of expressions */
    const DELIMITER = '"';
    /** @var string */
    const CARRIAGE_RETURN = "\r";
    /** @var string */
    const NEW_LINE = "\n";
    /** @var string */
    const ESCAPE_CHARACTER = '\\';

    /** @var string[] */
    const SEARCH_AND_REPLACE = [
        self::ESCAPE_CHARACTER => self::ESCAPE_CHARACTER . self::ESCAPE_CHARACTER,
        self::DELIMITER => '\\' . self::DELIMITER,
        self::CARRIAGE_RETURN => self::ESCAPE_CHARACTER . 'r',
        self::NEW_LINE => self::ESCAPE_CHARACTER . 'n'
    ];

    public static function fromString(string $value, CodeAnchorContract $codeAnchor = null): StringAtom
    {
        return new static($value, $codeAnchor);
    }

    public function getValue(): string
    {
        return parent::getValue();
    }

    public function evaluate(ContextContract $context): string
    {
        return $this->getValue();
    }

    public function __toString(): string
    {
        return self::DELIMITER
            . str_replace(
                array_keys(self::SEARCH_AND_REPLACE),
                array_values(self::SEARCH_AND_REPLACE),
                $this->getValue()
            )
            . self::DELIMITER;
    }
}

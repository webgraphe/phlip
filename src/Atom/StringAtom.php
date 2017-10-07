<?php

namespace Webgraphe\Phlip\Atom;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\ContextContract;

class StringAtom extends Atom
{
    /** @var string[] */
    const SEARCH_AND_REPLACE = [
        '\\' => '\\\\',
        '"' => '\\"',
        "\r" => '\\r',
        "\n" => '\\n'
    ];

    const DELIMITER = '"';

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

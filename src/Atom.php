<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Traits\AssertsStaticType;

abstract class Atom implements LexemeContract, FormContract
{
    use AssertsStaticType;

    /** @var string excludes white spaces, quotes, collection delimiters, keyword prefix, colon and comma */
    const IDENTIFIER_REGEX = '/^[^#\s\'"\(\)\[\]\{\}0-9][^#\s\'"\(\)\[\]\{\}]*$/';

    /** @var string|number|bool|null */
    private $value;
    /** @var CodeAnchorContract|null */
    private $codeAnchor;

    final protected function __construct($value, CodeAnchorContract $codeAnchor = null)
    {
        $this->value = $value;
        $this->codeAnchor = $codeAnchor;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function equals(FormContract $against): bool
    {
        return $against instanceof static && $against->getValue() === $this->getValue();
    }

    public function getCodeAnchor(): ?CodeAnchorContract
    {
        return $this->codeAnchor;
    }

    /**
     * @param string $value
     * @return string
     * @throws AssertionException
     */
    public static function assertValidIdentifier(string $value): string
    {
        if (preg_match(self::IDENTIFIER_REGEX, $value)) {
            return $value;
        }

        throw new AssertionException('Invalid identifier');
    }
}

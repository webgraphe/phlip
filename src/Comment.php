<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\LexemeContract;

class Comment implements LexemeContract
{
    const DELIMITER = ';';

    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = trim($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return "; {$this->getValue()}" . PHP_EOL;
    }
}

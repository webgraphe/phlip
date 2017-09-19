<?php

namespace Webgraphe\Phlip\Symbol;

use Webgraphe\Phlip\Symbol;

class CloseDelimiterSymbol extends Symbol
{
    const CHARACTER = ")";

    public function getValue(): string
    {
        return self::CHARACTER;
    }
}
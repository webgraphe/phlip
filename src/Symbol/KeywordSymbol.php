<?php

namespace Webgraphe\Phlip\Symbol;

use Webgraphe\Phlip\Symbol;

class KeywordSymbol extends Symbol
{
    const CHARACTER = ":";

    public function getValue(): string
    {
        return self::CHARACTER;
    }
}

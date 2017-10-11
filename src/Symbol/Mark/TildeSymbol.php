<?php

namespace Webgraphe\Phlip\Symbol\Mark;

use Webgraphe\Phlip\Symbol;

class TildeSymbol extends Symbol\Mark
{
    const CHARACTER = "~";

    public function getValue(): string
    {
        return self::CHARACTER;
    }
}

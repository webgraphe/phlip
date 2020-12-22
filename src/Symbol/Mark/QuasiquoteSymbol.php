<?php

namespace Webgraphe\Phlip\Symbol\Mark;

use Webgraphe\Phlip\Symbol;

class QuasiquoteSymbol extends Symbol\Mark
{
    const CHARACTER = "`";

    public function getValue(): string
    {
        return self::CHARACTER;
    }
}

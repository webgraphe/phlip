<?php

namespace Webgraphe\Phlip\Symbol;

use Webgraphe\Phlip\Symbol;

class DotSymbol extends Symbol
{
    const CHARACTER = ".";

    /**
     * @return string
     */
    public function getValue(): string
    {
        return self::CHARACTER;
    }
}

<?php

namespace Webgraphe\Phlip\Symbol\Opening;

use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class OpenListSymbol extends Opening
{
    const CHARACTER = "(";

    public function getValue(): string
    {
        return self::CHARACTER;
    }

    public function getRelatedClosingSymbol(): Closing
    {
        return Closing\CloseListSymbol::instance();
    }
}

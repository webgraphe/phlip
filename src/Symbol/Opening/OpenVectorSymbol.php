<?php

namespace Webgraphe\Phlip\Symbol\Opening;

use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;

class OpenVectorSymbol extends Opening
{
    const CHARACTER = "[";

    public function getValue(): string
    {
        return self::CHARACTER;
    }

    public function getRelatedClosingSymbol(): Closing
    {
        return Closing\CloseVectorSymbol::instance();
    }
}

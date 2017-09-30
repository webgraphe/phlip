<?php

namespace Webgraphe\Phlip\Symbol\Closing;

use Webgraphe\Phlip\Symbol\Closing;

class CloseVectorSymbol extends Closing
{
    const CHARACTER = "]";

    public function getValue(): string
    {
        return self::CHARACTER;
    }
}

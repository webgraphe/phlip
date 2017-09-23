<?php

namespace Webgraphe\Phlip\Stream;

use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Stream;

class LexemeStream extends Stream
{
    public static function fromLexemes(LexemeContract ...$lexemes)
    {
        return new static($lexemes, count($lexemes));
    }

    public function current(): LexemeContract
    {
        return parent::current();
    }
}

<?php

namespace Webgraphe\Phlip\Stream;

use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Stream;

/**
 * @method LexemeContract[] content()
 */
class LexemeStream extends Stream
{
    public static function fromLexemes(LexemeContract ...$lexemes)
    {
        return new static($lexemes, count($lexemes));
    }

    /**
     * @return LexemeContract
     * @throws \Webgraphe\Phlip\Exception\StreamException
     */
    public function current(): LexemeContract
    {
        return parent::current();
    }
}

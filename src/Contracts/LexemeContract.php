<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * A token extracted by a lexer.
 */
interface LexemeContract extends StringConvertibleContract
{
    public function getValue(): string;
}

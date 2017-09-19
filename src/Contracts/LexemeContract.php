<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * A token extracted by a lexer.
 */
interface LexemeContract extends StringConvertibleContract
{
    /**
     * @return string|number|bool|null
     */
    public function getValue();
}

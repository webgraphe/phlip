<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * A token extracted by a lexer.
 */
interface LexemeContract extends StringConvertibleContract
{
    /**
     * @return \stdClass|array|string|number|bool|null
     */
    public function getValue();
}

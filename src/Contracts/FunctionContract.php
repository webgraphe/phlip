<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * Not an absolute requirement, but guarantees an operation will be invokable in a context.
 * @see LanguageConstructContract A mutually exclusive contract for operations that require its forms not to be
 *      evaluated automatically.
 */
interface FunctionContract
{
    /**
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments);
}

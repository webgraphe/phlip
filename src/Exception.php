<?php

namespace Webgraphe\Phlip;

class Exception extends \Exception
{
    final public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

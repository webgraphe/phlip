<?php

namespace Webgraphe\Phlip;

use Exception;
use Throwable;

class PhlipException extends Exception
{
    final public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

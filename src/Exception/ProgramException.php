<?php

namespace Webgraphe\Phlip\Exception;

use Throwable;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception;

class ProgramException extends Exception
{
    private ContextContract $context;

    public static function fromContext(
        ContextContract $context,
        string $message,
        int $code = 0,
        Throwable $previous = null
    ) {
        $self = new static($message, $code, $previous);
        $self->context = $context;

        return $self;
    }

    public function getContext(): ContextContract
    {
        return $this->context;
    }
}

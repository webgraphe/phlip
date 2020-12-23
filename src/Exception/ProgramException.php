<?php

namespace Webgraphe\Phlip\Exception;

use Throwable;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\PhlipException;

class ProgramException extends PhlipException
{
    /** @var ContextContract */
    private $context;

    public static function fromContext(
        ContextContract $context,
        string $message,
        int $code = 0,
        Throwable $previous = null
    ): self {
        $self = new static($message, $code, $previous);
        $self->context = $context;

        return $self;
    }

    public function getContext(): ContextContract
    {
        return $this->context;
    }
}

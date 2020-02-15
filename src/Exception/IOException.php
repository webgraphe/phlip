<?php

namespace Webgraphe\Phlip\Exception;

use Throwable;
use Webgraphe\Phlip\Exception;

class IOException extends Exception
{
    private string $path = '';

    public static function fromPath(
        string $path,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ): IOException {
        $exception = new self($message, $code, $previous);
        $exception->path = $path;

        return $exception;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}

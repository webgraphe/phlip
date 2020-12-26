<?php

namespace Webgraphe\Phlip\Exception;

use Throwable;
use Webgraphe\Phlip\PhlipException;

class IOException extends PhlipException
{
    /** @var string */
    private $path = '';

    public static function fromPath(
        string $path,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ): IOException {
        $exception = new static($message, $code, $previous);
        $exception->path = $path;

        return $exception;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}

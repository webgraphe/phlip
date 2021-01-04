<?php

namespace Webgraphe\Phlip\Exception;

use Throwable;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\PhlipException;

class ProgramException extends PhlipException
{
    /** @var ScopeContract */
    private $scope;

    public static function fromScope(
        ScopeContract $scope,
        string $message,
        int $code = 0,
        Throwable $previous = null
    ): self {
        $self = new static($message, $code, $previous);
        $self->scope = $scope;

        return $self;
    }

    public function getScope(): ScopeContract
    {
        return $this->scope;
    }
}

<?php

namespace Webgraphe\Phlip\Exception;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception;

class EvaluationException extends Exception
{
    public static function fromExpression(ExpressionContract $expression = null, string $message = '', $extra = null)
    {
        $message = trim($message . PHP_EOL . (string)$expression);

        return new static($message);
    }
}

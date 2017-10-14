<?php

namespace Webgraphe\Phlip\Exception;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception;

class EvaluationException extends Exception
{
    public static function fromForm(
        FormContract $form,
        string $message,
        int $code = 0,
        \Throwable $previous = null
    ) {
        if ($previous) {
            $message .= " (from failed assertion; {$previous->getMessage()})";
        }
        $message = trim("$message; " . (string)$form);

        return new static($message, $code, $previous);
    }
}

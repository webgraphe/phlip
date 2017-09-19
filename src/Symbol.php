<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\LexemeContract;

abstract class Symbol implements LexemeContract
{
    private function __construct()
    {
    }

    public static function instance(): Symbol
    {
        static $instance;
        if (!$instance) {
            $instance = new static;
        }

        return $instance;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}

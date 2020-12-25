<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\LexemeContract;

abstract class Symbol implements LexemeContract
{
    final private function __construct()
    {
        // Symbols are immutable singletons
    }

    /**
     * @return static
     */
    public final static function instance(): Symbol
    {
        static $instance;
        if (!$instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}

<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Traits\AssertsStaticType;

abstract class Operation
{
    use AssertsStaticType;

    /**
     * @return string[]
     */
    abstract public function getIdentifiers(): array;
}

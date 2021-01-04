<?php

namespace Webgraphe\Phlip\Operation;

use Webgraphe\Phlip\Operation;

/**
 * Class AutomaticOperation
 * @package Webgraphe\Phlip\Operation
 */
abstract class AutomaticOperation extends Operation
{
    /**
     * @param mixed ...$arguments
     * @return mixed
     */
    abstract public function __invoke(...$arguments);
}

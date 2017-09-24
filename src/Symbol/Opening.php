<?php

namespace Webgraphe\Phlip\Symbol;

use Webgraphe\Phlip\Symbol;

abstract class Opening extends Symbol
{
    abstract public function getRelatedClosingSymbol(): Closing;
}

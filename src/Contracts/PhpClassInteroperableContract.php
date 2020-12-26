<?php

namespace Webgraphe\Phlip\Contracts;

interface PhpClassInteroperableContract
{
    public function isClassEnabled(string $class): bool;
}

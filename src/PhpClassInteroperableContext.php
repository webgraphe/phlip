<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\PhpClassInteroperableContract;

class PhpClassInteroperableContext extends Context implements PhpClassInteroperableContract
{
    /** @var string[] */
    private $enabledClasses = [];

    /**
     * @param string $class
     * @return static
     */
    public function enableClass(string $class): self
    {
        $this->enabledClasses[$class] = $class;

        return $this;
    }

    public function isClassEnabled(string $class): bool
    {
        return array_key_exists($class, $this->enabledClasses);
    }

}

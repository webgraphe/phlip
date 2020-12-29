<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;

class ContextAnchor
{
    /** @var ContextContract */
    private $context;

    public function __construct(ContextContract $context)
    {
        $this->context = $context;
    }

    /**
     * @return ContextContract
     */
    public function getContext(): ContextContract
    {
        return $this->context;
    }
}

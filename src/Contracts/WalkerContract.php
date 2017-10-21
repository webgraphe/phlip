<?php

namespace Webgraphe\Phlip\Contracts;

interface WalkerContract
{
    public function __invoke(FormContract $form): FormContract;
}

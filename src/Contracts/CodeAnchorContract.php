<?php

namespace Webgraphe\Phlip\Contracts;

interface CodeAnchorContract
{
    public function getSourceName(): ?string;

    public function getCode(): ?string;

    public function getOffset(): int;
}

<?php

namespace Tests\Webgraphe\Phlip;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function resolveRelativeProjectPath($relativePath): ?string
    {
        return realpath(dirname(__DIR__) . "/$relativePath");
    }
}

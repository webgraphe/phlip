<?php

namespace Webgraphe\Phlip\Tests;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Webgraphe\Phlip\Tests\Traits\GlobsRecursively;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use GlobsRecursively;

    protected function relativeProjectPath($relativePath): ?string
    {
        return realpath(dirname(__DIR__) . "/$relativePath");
    }
}

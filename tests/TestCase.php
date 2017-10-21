<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Tests\Traits\GlobsPathsRecursively;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use GlobsPathsRecursively;

    protected function relativeProjectPath($relativePath): ?string
    {
        return realpath(dirname(__DIR__) . "/$relativePath");
    }
}

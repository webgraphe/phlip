<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Tests\Traits\GlobsPathsRecursively;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use GlobsPathsRecursively;

    protected static function platformEol(string $string): string
    {
        switch (PHP_EOL) {
            case "\r\n":
                return str_replace([PHP_EOL, "\r", "\n"], ["\n", "\n", PHP_EOL], $string);
            case "\r":
                return str_replace(["\r\n", "\n"], [PHP_EOL, PHP_EOL], $string);
            case "\n":
                return str_replace(["\r\n", "\r"], [PHP_EOL, PHP_EOL], $string);
        }

        return $string;
    }

    protected function relativeProjectPath($relativePath): ?string
    {
        return realpath(sprintf('%s/%s', dirname(__DIR__), $relativePath));
    }
}

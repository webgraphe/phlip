<?php

namespace Webgraphe\Phlip\Tests;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class TestCase extends \PHPUnit\Framework\TestCase
{

    protected function relativeProjectPath($relativePath): ?string
    {
        return realpath(dirname(__DIR__) . "/$relativePath");
    }

    protected function globRecursive($path, callable $filter = null): array
    {
        $glob = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        while ($iterator->valid()) {
            /** @var RecursiveDirectoryIterator $dirIterator */
            $dirIterator = $iterator->getInnerIterator();
            if (!$dirIterator->isDot() && (!$filter || call_user_func($filter, $dirIterator))) {
                $glob[] = $dirIterator->getPathname();
            }

            $iterator->next();
        }

        return $glob;
    }
}

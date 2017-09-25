<?php

namespace Webgraphe\Phlip\Tests\Traits;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

trait GlobsPathsRecursively
{
    protected static function globRecursive($path, callable $filter = null): array
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
<?php

namespace Webgraphe\Phlip\Tests;

use PHPUnit\Framework\TestSuite;
use Webgraphe\Phlip\Tests\Traits\GlobsPathsRecursively;

/**
 * Used by phlipunit.
 */
class PhlipScriptTestSuite extends TestSuite
{
    use GlobsPathsRecursively;

    public function __construct(array $paths)
    {
        parent::__construct();

        $phlipTestFileFilter = function (\DirectoryIterator $iterator) {
            return $iterator->isFile() && preg_match('/Test\\.phlip$/', $iterator->getFilename());
        };

        $files = [];
        foreach ($paths as $path) {
            $fileInfo = new \SplFileInfo($path);
            if (!$fileInfo->isReadable()) {
                throw new \RuntimeException("Cannot open file/directory {$fileInfo->getPathname()}");
            }
            if ($fileInfo->isFile()) {
                if (!preg_match('/Test\\.phlip$/', $fileInfo->getFilename())) {
                    throw new \RuntimeException("{$fileInfo->getPathname()} is not a test file");
                }
                $realPath = $fileInfo->getRealPath();
                $files[$realPath] = $realPath;
            } elseif ($fileInfo->isDir()) {
                $directoryFiles = self::globRecursive($fileInfo->getRealPath(), $phlipTestFileFilter);
                $files = array_merge($files, array_combine($directoryFiles, $directoryFiles));
            }
        }

        foreach ($files as $file) {
            $this->addTest(new PhlipScriptTestCase($file));
        }
    }
}

<?php

namespace Webgraphe\Phlip\Tests;

use DirectoryIterator;
use PHPUnit\Framework\TestSuite;
use RuntimeException;
use SplFileInfo;
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

        $phlipTestFileFilter = function (DirectoryIterator $iterator) {
            return $iterator->isFile() && self::isTestFile($iterator->getFilename());
        };

        $files = [];
        foreach ($paths as $path) {
            $fileInfo = new SplFileInfo($path);
            if (!$fileInfo->isReadable()) {
                throw new RuntimeException("Cannot open file/directory {$fileInfo->getPathname()}");
            }
            if ($fileInfo->isFile()) {
                if (!self::isTestFile($fileInfo->getPathname())) {
                    throw new RuntimeException("{$fileInfo->getPathname()} is not a test file");
                }
                $realPath = $fileInfo->getRealPath();
                $files[$realPath] = $realPath;
            } elseif ($fileInfo->isDir()) {
                $directoryFiles = static::globRecursive($fileInfo->getRealPath(), $phlipTestFileFilter);
                $files = array_merge($files, array_combine($directoryFiles, $directoryFiles));
            }
        }

        foreach ($files as $file) {
            $this->addTest(new PhlipScriptTestCase($file));
        }
    }

    private static function isTestFile(string $name): bool
    {
        return (bool)preg_match('/Test\\.phlip$/', $name);
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Integration;

use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Tests\Traits\DefinesAssertionsInContexts;

class ScriptsTest extends TestCase
{
    use DefinesAssertionsInContexts;

    /**
     * @dataProvider scriptFiles
     * @param string $file
     */
    public function testScripts($file)
    {
        $context = $this->contextWithAsserts();
        Program::parseFile($file)->execute($context);
    }

    /**
     * Not your typical data provider. Loads .phlip scripts and evaluate (test) statements to retrieve their expression
     * lists and return them.
     *
     * NOTE: This method executes code that won't be tracked by PHPUnit's code coverage as is any code executed within
     * a data provider. This means any ContextContract related code or operation initialization calls won't be tracked.
     *
     * @return array
     */
    public function scriptFiles()
    {
        $files = self::globRecursive(
            $this->relativeProjectPath('tests/Integration/Scripts'),
            function (\DirectoryIterator $iterator) {
                return $iterator->isFile() && preg_match('/Test\\.phlip$/', $iterator->getFilename());
            }
        );
        return array_map(
            function (string $file) {
                return ['file' => $file];
            },
            array_combine($files, $files)
        );
    }
}

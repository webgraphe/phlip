<?php

namespace Webgraphe\Phlip\Tests\System;

use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Tests\Traits\DefinesAssertionsInContexts;

class ScriptsTest extends TestCase
{
    use DefinesAssertionsInContexts;

    /**
     * @dataProvider scriptFiles
     * @param string $file
     * @throws \Webgraphe\Phlip\Exception\IOException
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     */
    public function testScript($file)
    {
        $context = $this->contextWithAssertions();
        Program::parseFile($file)->execute($context);
    }

    /**
     * @return string[][]
     */
    public function scriptFiles(): array
    {
        $files = self::globRecursive(
            $this->relativeProjectPath('tests/Data/Scripts'),
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

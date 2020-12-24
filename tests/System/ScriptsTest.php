<?php

namespace Webgraphe\Phlip\Tests\System;

use DirectoryIterator;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Tests\Traits\DefinesAssertionsInContexts;

class ScriptsTest extends TestCase
{
    use DefinesAssertionsInContexts;

    /**
     * @dataProvider scriptFiles
     * @param string $file
     * @throws IOException
     * @throws LexerException
     * @throws ParserException
     * @throws AssertionException
     * @throws ContextException
     * @throws ProgramException
     */
    public function testScript(string $file)
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
            function (DirectoryIterator $iterator) {
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

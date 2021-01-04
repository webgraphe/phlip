<?php

namespace Webgraphe\Phlip\Tests\System;

use DirectoryIterator;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Tests\Traits\DefinesAssertionsInScopes;

class ScriptsTest extends TestCase
{
    use DefinesAssertionsInScopes;

    /**
     * @dataProvider scriptFiles
     * @param string $file
     * @throws IOException
     * @throws LexerException
     * @throws ParserException
     * @throws AssertionException
     * @throws ScopeException
     * @throws ProgramException
     */
    public function testScript(string $file)
    {
        Program::parseFile($file)->execute($this->scopeWithAssertions());
    }

    /**
     * @return string[][]
     */
    public function scriptFiles(): array
    {
        $files = static::globRecursive(
            $this->relativeProjectPath('tests/Data/Scripts'),
            function (DirectoryIterator $iterator) {
                return $iterator->isFile() && self::isTestFile($iterator->getFilename());
            }
        );

        return array_map(
            function (string $file) {
                return ['file' => $file];
            },
            array_combine($files, $files)
        );
    }

    private static function isTestFile(string $name): bool
    {
        return (bool)preg_match('/Test\\.phlip$/', $name);
    }
}

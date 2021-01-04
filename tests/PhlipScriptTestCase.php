<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\Traits\DefinesAssertionsInScopes;

/**
 * Used by phlipunit.
 * @see \Webgraphe\Phlip\Tests\TestCase is to be used for regular PHP test cases.
 */
class PhlipScriptTestCase extends \PHPUnit\Framework\TestCase
{
    use DefinesAssertionsInScopes;

    private $file;

    public function __construct(string $file)
    {
        parent::__construct('testScript');
        $this->file = $file;
    }

    /**
     * @throws IOException
     * @throws LexerException
     * @throws ParserException
     * @throws AssertionException
     * @throws ScopeException
     * @throws ProgramException
     */
    public function testScript()
    {
        $scope = $this->scopeWithAssertions();
        Program::parseFile($this->file)->execute($scope);
    }

    public function count(): int
    {
        return 1;
    }

    public function toString(): string
    {
        return $this->file;
    }
}

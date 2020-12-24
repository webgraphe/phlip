<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\Traits\DefinesAssertionsInContexts;

/**
 * Used by phlipunit.
 * @see \Webgraphe\Phlip\Tests\TestCase is to be used for regular PHP test cases.
 */
class PhlipScriptTestCase extends \PHPUnit\Framework\TestCase
{
    use DefinesAssertionsInContexts;

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
     * @throws ContextException
     * @throws ProgramException
     */
    public function testScript()
    {
        $context = $this->contextWithAssertions();
        Program::parseFile($this->file)->execute($context);
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

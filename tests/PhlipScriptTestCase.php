<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\Traits\DefinesAssertionsInContexts;

/**
 * Used by phlipunit.
 * @see \Webgraphe\Phlip\Tests\TestCase is to be used for regular PHP test cases.
 */
class PhlipScriptTestCase extends \PHPUnit\Framework\TestCase
{
    use DefinesAssertionsInContexts;

    /** @var string */
    private $file;

    public function __construct(string $file)
    {
        parent::__construct('testScript');
        $this->file = $file;
    }

    /**
     * @throws \Webgraphe\Phlip\Exception
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     * @throws \Webgraphe\Phlip\Exception\ProgramException
     */
    public function testScript()
    {
        $context = $this->contextWithAsserts();
        Program::parseFile($this->file)->execute($context);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->file;
    }
}

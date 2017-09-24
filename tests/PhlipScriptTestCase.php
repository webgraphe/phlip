<?php

namespace Webgraphe\Phlip\Tests;

use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct\CallablePrimaryFunctionOperation;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\Traits\HooksAssertionsInContexts;

/**
 * Used by phlipunit.
 * @see \Webgraphe\Phlip\Tests\TestCase is to be used for regular PHP test cases.
 */
class PhlipScriptTestCase extends \PHPUnit\Framework\TestCase
{
    use HooksAssertionsInContexts;

    /** @var string */
    private $file;

    public function __construct(string $file)
    {
        parent::__construct('testScript');
        $this->file = $file;
    }

    public function testScript()
    {
        $context = $this->contextWithAsserts();
        Program::parseFile($this->file)->execute($context);
    }

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return $this->file;
    }
}

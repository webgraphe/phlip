<?php

namespace Webgraphe\Phlip\Tests\Unit;

use DateTime;
use Exception;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Scope;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\Interop\CloneOperation;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class ProgramTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testProgram()
    {
        $program = new Program(
            new FormList(
                NumberAtom::fromString('1'),
                NumberAtom::fromString('2'),
                NumberAtom::fromString('3')
            )
        );
        $this->assertEquals(3, $program->execute(new Scope()));
    }

    /**
     * @throws Exception
     */
    public function testProgramFailure()
    {
        $message = 'Fail';

        $scope = new Scope();
        $scope->define(
            'fail',
            function () use ($message) {
                throw new Exception($message);
            }
        );

        try {
            Program::parse('(fail)')->execute($scope);
            $this->fail("Failed to fail");
        } catch (ProgramException $e) {
            $this->assertInstanceOf(Exception::class, $previous = $e->getPrevious());
            $this->assertEquals($message, $previous->getMessage());
            $this->assertEquals($scope, $e->getScope());
        }
    }

    /**
     * @throws Exception
     * @throws LexerException
     * @throws ParserException
     */
    public function testParse()
    {
        $this->assertEquals(3, Program::parse('1 2 3')->execute(new Scope()));
    }

    /**
     * @throws Exception
     * @throws IOException
     * @throws LexerException
     * @throws ParserException
     */
    public function testParseFile()
    {
        $file = __DIR__ . '/ProgramTest.phlip';
        $program = Program::parseFile($file);
        $this->assertEquals(file_get_contents($file), '"' . $program->execute(new Scope()) . '"');
    }

    /**
     * @throws LexerException
     * @throws ParserException
     * @throws ProgramException
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testExecuteWithParameters()
    {
        $this->assertEquals(
            (string)(new FormList(
                NumberAtom::fromString('1'),
                NumberAtom::fromString('2'),
                NumberAtom::fromString('3')
            )),
            Program::parse('`(,$0 ,$1 ,$2)')->execute(new Scope(), 1, 2, 3)
        );
    }

    /**
     * @throws IOException
     * @throws LexerException
     * @throws ParserException
     */
    public function testParseNonExistentFile()
    {
        $this->expectException(IOException::class);
        Program::parseFile("Seriously you named a file after this very sentence");
    }

    /**
     * @throws IOException
     * @throws LexerException
     * @throws ParserException
     */
    public function testParseNonReadableFile()
    {
        $path = '/dev/rtc0';

        $this->expectException(IOException::class);

        try {
            Program::parseFile($path);
        } catch (IOException $e) {
            $this->assertEquals($path, $e->getPath());

            throw $e;
        }
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     * @throws LexerException
     * @throws ParserException
     * @throws ProgramException
     */
    public function testNonInteroperableScopeOnInterop()
    {
        $scope = Phlipy::basic()->withOperation(new CloneOperation())->getScope();
        $scope->define('now', new DateTime());

        $this->expectException(ScopeException::class);
        $this->expectExceptionMessage("Class 'DateTime' requires an PHP interoperable scope");

        Program::parse('(clone now)')->execute($scope);
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Unit;

use DateTime;
use Exception;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormCollection\ProperList;
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
            new ProperList(
                NumberAtom::fromString('1'),
                NumberAtom::fromString('2'),
                NumberAtom::fromString('3')
            )
        );
        $this->assertEquals(3, $program->execute(new Context()));
    }

    /**
     * @throws Exception
     */
    public function testProgramFailure()
    {
        $message = 'Fail';

        $context = new Context();
        $context->define(
            'fail',
            function () use ($message) {
                throw new Exception($message);
            }
        );

        try {
            Program::parse('(fail)')->execute($context);
            $this->fail("Failed to fail");
        } catch (ProgramException $e) {
            $this->assertInstanceOf(Exception::class, $previous = $e->getPrevious());
            $this->assertEquals($message, $previous->getMessage());
            $this->assertEquals($context, $e->getContext());
        }
    }

    /**
     * @throws Exception
     * @throws LexerException
     * @throws ParserException
     */
    public function testParse()
    {
        $this->assertEquals(3, Program::parse('1 2 3')->execute(new Context()));
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
        $this->assertEquals(file_get_contents($file), '"' . $program->execute(new Context()) . '"');
    }

    /**
     * @throws LexerException
     * @throws ParserException
     * @throws ProgramException
     * @throws AssertionException
     * @throws ContextException
     */
    public function testExecuteWithParameters()
    {
        $this->assertEquals(
            (string)(new ProperList(
                NumberAtom::fromString('1'),
                NumberAtom::fromString('2'),
                NumberAtom::fromString('3')
            )),
            Program::parse('`(,$0 ,$1 ,$2)')->execute(new Context(), 1, 2, 3)
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
     * @throws ContextException
     * @throws LexerException
     * @throws ParserException
     * @throws ProgramException
     */
    public function testNonInteroperableContextOnInterop()
    {
        $context = Phlipy::passive()->withOperation(new CloneOperation())->getContext();
        $context->define('now', new DateTime());

        $this->expectException(ContextException::class);
        $this->expectExceptionMessage("Class 'DateTime' requires an PHP interoperable context");

        Program::parse('(clone now)')->execute($context);
    }
}

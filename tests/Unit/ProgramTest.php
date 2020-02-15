<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Exception;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\FormCollection\ProperList;
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
        $this->assertEquals(3, $program->execute(new Context));
    }

    /**
     * @throws Exception
     * @throws LexerException
     * @throws ParserException
     */
    public function testParse()
    {
        $this->assertEquals(3, Program::parse('1 2 3')->execute(new Context));
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
        $this->assertEquals(file_get_contents($file), '"' . $program->execute(new Context) . '"');
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
}

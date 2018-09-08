<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\IOException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class ProgramTest extends TestCase
{
    /**
     * @throws \Exception
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
     * @throws \Exception
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     */
    public function testParse()
    {
        $this->assertEquals(3, Program::parse('1 2 3')->execute(new Context));
    }

    /**
     * @throws \Exception
     * @throws \Webgraphe\Phlip\Exception\IOException
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     */
    public function testParseFile()
    {
        $file = __DIR__ . '/ProgramTest.phlip';
        $program = Program::parseFile($file);
        $this->assertEquals(file_get_contents($file), '"' . $program->execute(new Context) . '"');
    }

    /**
     * @throws \Webgraphe\Phlip\Exception\IOException
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     */
    public function testParseNonExistentFile()
    {
        $this->expectException(IOException::class);
        Program::parseFile("Seriously you named a file after this very sentence");
    }

    /**
     * @throws IOException
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     */
    public function testParseNonReadableFile()
    {
        $this->expectException(IOException::class);
        Program::parseFile('/dev/rtc0');
    }
}

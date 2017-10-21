<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class ProgramTest extends TestCase
{
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

    public function testParse()
    {
        $this->assertEquals(3, Program::parse('1 2 3')->execute(new Context));
    }

    public function testParseFile()
    {
        $file = __DIR__ . '/ProgramTest.phlip';
        $program = Program::parseFile($file);
        $this->assertEquals(file_get_contents($file), '"' . $program->execute(new Context) . '"');
    }

    public function testParseNonExistentFile()
    {
        $this->expectException(ProgramException::class);
        Program::parseFile("Seriously you named a file after this very sentence");
    }

    public function testParseNonReadableFile()
    {
        $this->expectException(ProgramException::class);
        Program::parseFile('/dev/rtc0');
    }
}

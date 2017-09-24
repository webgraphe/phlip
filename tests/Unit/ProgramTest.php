<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\Program;

class ProgramTest extends TestCase
{
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
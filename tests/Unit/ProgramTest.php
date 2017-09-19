<?php

namespace Tests\Webgraphe\Phlip\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Program;

class ProgramTest extends TestCase
{
    public function testFileNotFoundException()
    {
        $this->expectException(\RuntimeException::class);
        Program::parseFile(' a file that "hopefully" does not exist ');
    }

    public function testParse()
    {
        $source = <<<SOURCE
(define (ticker) null)

; Re-set ticker to use a variable in a lexical closure instead
(let
    (
        (x 0)
        ((plus-1 x) (+ x 1))
    )
    (set (ticker) (set x (plus-1 x)) x)
)

(assert (! (defined? x)))
(assert (= (ticker) 1))
(define x 0)
(assert (defined? x))
(assert (= (ticker) 2))
(assert (= x 0))
(assert (= (get x) x))

; A program SHOULD only contain expression list, however an atom is accepted if it's the last evaluable expression
"\\tThis program has ended\\r\\n"

; There can be comments after the last evaluable expression

SOURCE;

        $program = Program::parse($source);
        $this->assertInstanceOf(Program::class, $program);

        return $program;
    }

    /**
     * @depends testParse
     * @param Program $program
     */
    public function testProgramExecute(Program $program)
    {
        $this->assertEquals("\tThis program has ended\r\n", $program->execute((new PhlipyContext)->withAssert()));
    }
}

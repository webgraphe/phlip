<?php

use Webgraphe\Phlip\PhlipException;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class ReadmeTest extends TestCase
{
    /**
     * @throws PhlipException
     */
    public function testExample()
    {
        $program = Program::parse('(lambda (x) (* x x))');
        $context = Phlipy::basic()->getContext();
        $square = $program->execute($context);
        $this->assertEquals(M_PI ** 2, $square(M_PI));
    }
}

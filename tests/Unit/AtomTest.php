<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Tests\TestCase;

abstract class AtomTest extends TestCase
{
    abstract protected function createAtom(CodeAnchor $anchor = null): Atom;
    abstract protected function testEvaluation();
    abstract protected function testStringConvertible();

    public function testNullCodeAnchor()
    {
        $this->assertNull($this->createAtom()->getCodeAnchor());
    }

    public function testNonNullCodeAnchor()
    {
        $codeAnchor = new CodeAnchor(CharacterStream::fromString(''));
        $this->assertEquals($codeAnchor, $this->createAtom($codeAnchor)->getCodeAnchor());
    }

    public function testEquals()
    {
        $codeAnchor = new CodeAnchor(CharacterStream::fromString(''));
        $this->assertTrue($this->createAtom()->equals($this->createAtom($codeAnchor)));
    }
}

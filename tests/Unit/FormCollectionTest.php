<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Tests\TestCase;

abstract class FormCollectionTest extends TestCase
{
    abstract protected function createFormCollection(CodeAnchor $anchor = null): FormCollection;

    abstract public function testEvaluation();
    abstract public function testStringConvertible();

    public function testNullCodeAnchor()
    {
        $this->assertNull($this->createFormCollection()->getCodeAnchor());
    }

    public function testNonNullCodeAnchor()
    {
        $codeAnchor = new CodeAnchor(CharacterStream::fromString(''));
        $this->assertEquals($codeAnchor, $this->createFormCollection($codeAnchor)->getCodeAnchor());
    }

    public function testEqualsWithDifferentCodeAnchors()
    {
        $codeAnchor = new CodeAnchor(CharacterStream::fromString(''));
        $this->assertTrue($this->createFormCollection()->equals($this->createFormCollection($codeAnchor)));
    }

    public function testEqualsWithNonFormCollection()
    {
        $this->assertFalse($this->createFormCollection()->equals(NumberAtom::fromString('42')));
    }
}

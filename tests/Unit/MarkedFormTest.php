<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Contracts\CodeAnchorContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\MarkedForm;
use Webgraphe\Phlip\Stream\CharacterStream;
use Webgraphe\Phlip\Tests\TestCase;

abstract class MarkedFormTest extends TestCase
{
    abstract protected function createForm(CodeAnchorContract $codeAnchor = null): FormContract;
    abstract protected function createMarkedForm(FormContract $form): MarkedForm;
    abstract public function testEvaluation();

    public function testNullCodeAnchor()
    {
        $this->assertNull($this->createMarkedForm($this->createForm())->getCodeAnchor());
    }

    public function testNonNullCodeAnchor()
    {
        $codeAnchor = new CodeAnchor(CharacterStream::fromString(''));
        $this->assertEquals($codeAnchor, $this->createMarkedForm($this->createForm($codeAnchor))->getCodeAnchor());
    }

    public function testStringConvertible()
    {
        $markedForm = $this->createMarkedForm($this->createForm());
        $this->assertEquals(
            $markedForm->getMarkSymbol() . $this->createForm(),
            (string)$markedForm
        );
    }

    public function testCreateNew()
    {
        $markedForm = $this->createMarkedForm($this->createForm());
        $this->assertTrue(
            $markedForm->equals($this->createMarkedForm($this->createForm())->createNew($this->createForm()))
        );
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Context\PhlipyContext;
use Webgraphe\Phlip\Program;

class ScriptTest extends TestCase
{
    public function testScripts()
    {
        $this->assertNotEmpty($pathToScript = $this->resolveRelativeProjectPath('tests/scripts'));
        foreach (glob("$pathToScript/*.phlip") as $file) {
            Program::parseFile($file)->execute((new PhlipyContext)->withAssert());
        }
    }
}

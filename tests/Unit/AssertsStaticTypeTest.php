<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class AssertsStaticTypeTest extends TestCase
{
    use AssertsStaticType;

    public function testAssertStaticTypeOnSelf()
    {
        $this->assertEquals($this, $this->assertStaticType($this));
    }

    public function testAssertStaticTypeOnSomethingElse()
    {
        $this->expectException(\RuntimeException::class);
        $this->assertStaticType("Something else");
    }
}
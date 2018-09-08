<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class AssertsStaticTypeTest extends TestCase
{
    use AssertsStaticType;

    /**
     * @throws AssertionException
     */
    public function testAssertStaticTypeOnSelf()
    {
        $this->assertEquals($this, $this->assertStaticType($this));
    }

    /**
     * @throws AssertionException
     */
    public function testAssertStaticTypeOnSomethingElse()
    {
        $this->expectException(AssertionException::class);
        $this->assertStaticType("Something else");
    }
}

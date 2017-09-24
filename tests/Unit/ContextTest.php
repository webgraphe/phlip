<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\ContextException;

class ContextTest extends TestCase
{
    public function testRedefinition()
    {
        $context = new Context;
        $context->define('x', 2);

        $this->expectException(ContextException::class);
        $context->define('x', 3);
    }

    public function testSetUndefined()
    {
        $context = new Context;

        $this->expectException(ContextException::class);
        $context->set('x', 2);
    }

    public function testLetAgain()
    {
        $child = new Context(new Context());
        $child->let('x', 2);

        $this->expectException(ContextException::class);
        $child->let('x', 3);
    }

    public function testGetUndefined()
    {
        $context = new Context();

        $this->expectException(ContextException::class);
        $context->get('x');
    }

    public function testParentHas()
    {
        $child = ($parent = new Context())->stack();
        $parent->define('x', 2);
        $this->assertTrue($child->has('x'));
        $this->assertFalse($child->has('y'));
    }
}

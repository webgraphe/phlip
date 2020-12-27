<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Exception;
use Webgraphe\Phlip\Operation\StandardOperation;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\ContextException;

class ContextTest extends TestCase
{
    public function testInitialStates()
    {
        $context = new Context();
        $this->assertEquals(0, $context->getTicks());
        $this->assertEmpty($context->getFormStack());
    }

    /**
     * @throws ContextException
     */
    public function testRedefinition()
    {
        $context = new Context();
        $context->define('x', 2);

        $this->expectException(ContextException::class);
        $context->define('x', 3);
    }

    /**
     * @throws ContextException
     */
    public function testStackedDefinition()
    {
        $child = ($parent = new Context())->stack();
        $this->assertFalse($parent->has('key'));
        $this->assertNotEquals($child, $parent);
        $child->define('key', 'value');
        $this->assertTrue($parent->has('key'));
        $this->assertEquals($child->get('key'), $parent->get('key'));
        $child->set('key', 'new value');
        $this->assertEquals('new value', $parent->get('key'));
    }

    /**
     * @throws ContextException
     */
    public function testSet()
    {
        $context = new Context();
        $context->define('key', 'old value');
        $oldValue = $context->set('key', 'new value');
        $this->assertEquals('old value', $oldValue);
        $this->assertEquals('new value', $context->get('key'));
    }

    /**
     * @throws ContextException
     */
    public function testSetUndefined()
    {
        $context = new Context();

        $this->expectException(ContextException::class);
        $context->set('x', 2);
    }

    public function testLetAgain()
    {
        $child = (new Context())->stack();
        $child->let('x', 2);

        $this->expectException(ContextException::class);
        $child->let('x', 3);
    }

    /**
     * @throws ContextException
     */
    public function testGetUndefined()
    {
        $context = new Context();

        $this->expectException(ContextException::class);
        $context->get('x');
    }

    /**
     * @throws ContextException
     */
    public function testParentHas()
    {
        $child = ($parent = new Context())->stack();
        $parent->define('x', 2);
        $this->assertTrue($child->has('x'));
        $this->assertFalse($child->has('y'));
    }

    public function testOperationBoundToDifferentContext()
    {
        $operation = new class() extends StandardOperation {
            public function getIdentifiers(): array
            {
                return [];
            }

            public function __invoke(...$arguments)
            {
                throw new Exception("Test operation");
            }

            public function isBounded(): bool
            {
                return true;
            }
        };

        $this->expectException(ContextException::class);
        $this->expectExceptionMessage(
            "Failed to define operation 'out-of-context'; operation is bound to a different context"
        );

        (new Context())->define('out-of-context', $operation);
    }

    public function testOperationAlreadyBound()
    {
        $this->expectException(ContextException::class);
        $this->expectExceptionMessage(
            "instance is already bound to another context"
        );

        new class() extends StandardOperation {
            public function __construct()
            {
                $this->withBoundedContext(new Context())->withBoundedContext(new Context());
            }

            public function getIdentifiers(): array
            {
                return [];
            }

            public function __invoke(...$arguments)
            {
                throw new Exception("Test operation");
            }

            public function isBounded(): bool
            {
                return true;
            }
        };
    }

    public function testOperationNotBoundToAContext()
    {
        $operation = new class() extends StandardOperation {
            public function getIdentifiers(): array
            {
                return [];
            }

            public function __invoke(...$arguments)
            {
                throw new Exception("Test operation");
            }

            public function isBounded(): bool
            {
                return (bool)$this->assertBoundedContext();
            }
        };

        $this->expectException(ContextException::class);
        $this->expectExceptionMessage(
            "is not bound to a context"
        );

        $operation->isBounded();
    }
}

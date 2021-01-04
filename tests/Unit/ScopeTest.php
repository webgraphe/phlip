<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Exception;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Operation\AutomaticOperation;
use Webgraphe\Phlip\Scope;
use Webgraphe\Phlip\Tests\TestCase;

class ScopeTest extends TestCase
{
    public function testInitialStates()
    {
        $scope = new Scope();
        $this->assertEquals(0, $scope->getTicks());
        $this->assertEmpty($scope->getFormStack());
    }

    /**
     * @throws ScopeException
     */
    public function testRedefinition()
    {
        $scope = new Scope();
        $scope->define('x', 2);

        $this->expectException(ScopeException::class);
        $scope->define('x', 3);
    }

    /**
     * @throws ScopeException
     */
    public function testStackedDefinition()
    {
        $child = ($parent = new Scope())->stack();
        $this->assertFalse($parent->has('key'));
        $this->assertNotEquals($child, $parent);
        $child->define('key', 'value');
        $this->assertTrue($parent->has('key'));
        $this->assertEquals($child->get('key'), $parent->get('key'));
        $child->set('key', 'new value');
        $this->assertEquals('new value', $parent->get('key'));
    }

    /**
     * @throws ScopeException
     */
    public function testSet()
    {
        $scope = new Scope();
        $scope->define('key', 'old value');
        $oldValue = $scope->set('key', 'new value');
        $this->assertEquals('old value', $oldValue);
        $this->assertEquals('new value', $scope->get('key'));
    }

    /**
     * @throws ScopeException
     */
    public function testSetUndefined()
    {
        $scope = new Scope();

        $this->expectException(ScopeException::class);
        $scope->set('x', 2);
    }

    public function testLetAgain()
    {
        $child = (new Scope())->stack();
        $child->let('x', 2);

        $this->expectException(ScopeException::class);
        $child->let('x', 3);
    }

    /**
     * @throws ScopeException
     */
    public function testGetUndefined()
    {
        $scope = new Scope();

        $this->expectException(ScopeException::class);
        $scope->get('x');
    }

    /**
     * @throws ScopeException
     */
    public function testParentHas()
    {
        $child = ($parent = new Scope())->stack();
        $parent->define('x', 2);
        $this->assertTrue($child->has('x'));
        $this->assertFalse($child->has('y'));
    }

    public function testOperationBoundedToAScope()
    {
        $operation = new class() extends AutomaticOperation {
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
                return (bool)$this->assertBoundedScope();
            }
        };

        $operation->bindToScope(new Scope());
        $this->assertTrue($operation->isBounded());
    }

    public function testOperationNotBoundedToAScope()
    {
        $operation = new class() extends AutomaticOperation {
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
                return (bool)$this->assertBoundedScope();
            }
        };

        $this->expectException(ScopeException::class);
        $this->expectExceptionMessage("is not bounded to a scope");

        $operation->isBounded();
    }
}

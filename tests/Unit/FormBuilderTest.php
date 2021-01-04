<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Scope;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Tests\TestCase;

class FormBuilderTest extends TestCase
{
    /**
     * @throws AssertionException
     */
    public function testForm()
    {
        $builder = new FormBuilder();

        $identifier = IdentifierAtom::fromString('identifier');
        $this->assertTrue($identifier->equals($builder->asForm($identifier)));

        $list = new FormCollection\FormList($identifier);
        $this->assertTrue($list->equals($builder->asForm($list)));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testNull()
    {
        $builder = new FormBuilder();

        $this->assertNull((new Scope())->execute($builder->asForm(null)));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testTrue()
    {
        $builder = new FormBuilder();

        $this->assertNotEmpty((new Scope())->execute($builder->asForm(true)));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testFalse()
    {
        $builder = new FormBuilder();

        $this->assertEmpty((new Scope())->execute($builder->asForm(false)));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testString()
    {
        $builder = new FormBuilder();

        $this->assertEquals("string", (new Scope())->execute($builder->asForm("string")));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testNumeric()
    {
        $builder = new FormBuilder();

        $this->assertEquals(0, (new Scope())->execute($builder->asForm(0)));
        $this->assertEquals(42, (new Scope())->execute($builder->asForm(42)));
        $this->assertEquals(3.14, (new Scope())->execute($builder->asForm(3.14)));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testVector()
    {
        $builder = new FormBuilder();

        $this->assertEquals([], (new Scope())->execute($builder->asForm([])));
        $this->assertEquals([1, 2, 3], (new Scope())->execute($builder->asForm([1, 2, 3])));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testMap()
    {
        $builder = new FormBuilder();

        $this->assertEquals((object)[], (new Scope())->execute($builder->asForm((object)[])));
        $object = (object)['key' => 'value', 'values' => [1, 2]];
        $this->assertEquals($object, (new Scope())->execute($builder->asForm($object)));
    }

    /**
     * @throws AssertionException
     */
    public function testUnhandledType()
    {
        $builder = new FormBuilder();

        $this->expectException(AssertionException::class);
        $builder->asForm($this);
    }
}

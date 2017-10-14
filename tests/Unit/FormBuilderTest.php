<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Tests\TestCase;

class FormBuilderTest extends TestCase
{
    public function testForm()
    {
        $builder = new FormBuilder;

        $identifier = IdentifierAtom::fromString('identifier');
        $this->assertTrue($identifier->equals($builder->asForm($identifier)));

        $list = new FormCollection\ProperList($identifier);
        $this->assertTrue($list->equals($builder->asForm($list)));
    }

    public function testNull()
    {
        $builder = new FormBuilder;

        $this->assertNull($builder->asForm(null)->evaluate(new Context));
    }

    public function testTrue()
    {
        $builder = new FormBuilder;

        $this->assertNotEmpty($builder->asForm(true)->evaluate(new Context));
    }

    public function testFalse()
    {
        $builder = new FormBuilder;

        $this->assertEmpty($builder->asForm(false)->evaluate(new Context));
    }

    public function testString()
    {
        $builder = new FormBuilder;

        $this->assertEquals("string", $builder->asForm("string")->evaluate(new Context));
    }

    public function testNumeric()
    {
        $builder = new FormBuilder;

        $this->assertEquals(0, $builder->asForm(0)->evaluate(new Context));
        $this->assertEquals(42, $builder->asForm(42)->evaluate(new Context));
        $this->assertEquals(3.14, $builder->asForm(3.14)->evaluate(new Context));
    }

    public function testVector()
    {
        $builder = new FormBuilder;

        $this->assertEquals([], $builder->asForm([])->evaluate(new Context));
        $this->assertEquals([1, 2, 3], $builder->asForm([1, 2, 3])->evaluate(new Context));
    }

    public function testMap()
    {
        $builder = new FormBuilder;

        $this->assertEquals((object)[], $builder->asForm((object)[])->evaluate(new Context));
        $object = (object)['key' => 'value', 'values' => [1, 2]];
        $this->assertEquals($object, $builder->asForm($object)->evaluate(new Context));
    }

    public function testUnhandledType()
    {
        $builder = new FormBuilder;

        $this->expectException(AssertionException::class);
        $builder->asForm($this);
    }
}

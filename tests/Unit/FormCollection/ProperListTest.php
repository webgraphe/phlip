<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\FormCollection\Vector;

class ProperListTest extends FormCollectionTest implements PrimaryOperationContract
{
    /**
     * @param CodeAnchor|null $anchor
     * @return FormCollection|ProperList
     * @throws AssertionException
     */
    protected function createFormCollection(CodeAnchor $anchor = null): FormCollection
    {
        return new ProperList(
            IdentifierAtom::fromString('test', $anchor),
            KeywordAtom::fromString('keyword'),
            StringAtom::fromString('string'),
            new Vector(NumberAtom::fromString('1'), NumberAtom::fromString('2'), NumberAtom::fromString('3')),
            new Map(new ProperList(StringAtom::fromString('key'), StringAtom::fromString('value')))
        );
    }

    /**
     * @throws AssertionException
     * @throws \Exception
     */
    public function testStringConvertible()
    {
        $this->assertEquals('()', (string)new ProperList);
        $this->assertEquals(
            '(test 1 2 3)',
            (string)new ProperList(
                IdentifierAtom::fromString('test'),
                NumberAtom::fromString('1'),
                NumberAtom::fromString('2'),
                NumberAtom::fromString('3')
            )
        );
    }

    /**
     * @throws AssertionException
     * @throws \Exception
     * @throws \Webgraphe\Phlip\Exception\ContextException
     */
    public function testEvaluation()
    {
        $list = $this->createFormCollection();
        $context = new Context;
        $context->define(IdentifierAtom::assertStaticType($list->assertHead())->getValue(), $this);
        $this->assertTrue($list->getTail()->equals(new ProperList(...$list->evaluate($context))));

        $list = $this->createFormCollection();
        $context = new Context;
        $context->define(
            IdentifierAtom::assertStaticType($list->assertHead())->getValue(),
            function () {
                return func_get_args();
            }
        );
        $this->assertEquals(
            [
                KeywordAtom::fromString('keyword'),
                'string',
                [1, 2, 3],
                (object)['key' => 'value'],
            ],
            $list->evaluate($context)
        );
    }

    /**
     * @throws AssertionException
     * @throws \Exception
     */
    public function testEmptyList()
    {
        $list = new ProperList;
        $this->assertEmpty($list->all());
        $this->assertCount(0, $list);
        $this->assertNull($list->getHead());
        $this->assertTrue($list->equals($list->getTail()));
        $this->assertNull($list->evaluate(new Context));

        $this->expectException(AssertionException::class);
        $list->assertHead();
    }

    /**
     * @throws AssertionException
     * @throws \Exception
     */
    public function testAsList()
    {
        $list = new ProperList;
        $this->assertEquals($list, ProperList::asList($list));

        $identifier = IdentifierAtom::fromString('identifier');
        $this->assertTrue(ProperList::asList($identifier)->equals(new ProperList($identifier)));
    }

    /**
     * @throws AssertionException
     * @throws \Webgraphe\Phlip\Exception\ContextException
     */
    public function testNotCallable()
    {
        $list = new ProperList(IdentifierAtom::fromString('not-callable'));
        $context = new Context;
        $context->define('not-callable', "This is a callable");

        $this->expectException(AssertionException::class);
        $list->evaluate($context);
    }

    public function __invoke(ContextContract $context, FormContract ...$forms): array
    {
        return $forms;
    }

    /**
     * @throws AssertionException
     * @throws \Webgraphe\Phlip\Exception\ContextException
     */
    public function testFailedEvaluation()
    {
        $context = new Context;
        $context->define(
            'fail',
            function () {
                throw new \Exception('Fail', 666);
            }
        );
        $list = new ProperList(IdentifierAtom::fromString('fail'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Fail');
        $this->expectExceptionCode(666);
        $list->evaluate($context);
    }

    /**
     * @throws AssertionException
     * @throws \Exception
     */
    public function testMap()
    {
        $this->assertEquals(
            '((test) (42) (3.14) (#keyword))',
            (string)(new ProperList(
                IdentifierAtom::fromString('test'),
                NumberAtom::fromString('42'),
                NumberAtom::fromString('3.14'),
                KeywordAtom::fromString('keyword')
            ))->map(function (FormContract $form) {
                return ProperList::asList($form);
            })
        );
    }

    /**
     * @param WalkerContract $walker
     * @param FormContract[] ...$forms
     * @return FormContract[]
     */
    public function walk(WalkerContract $walker, FormContract ...$forms): array
    {
        return array_map($walker, $forms);
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Unit\FormCollection;

use Exception;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\CodeAnchor;
use Webgraphe\Phlip\Scope;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\ManualOperationContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\FormCollection\Vector;
use Webgraphe\Phlip\Tests\Unit\FormCollectionTest;

class ProperListTest extends FormCollectionTest implements ManualOperationContract
{
    /**
     * @param CodeAnchor|null $anchor
     * @return FormCollection|FormList
     * @throws AssertionException
     */
    protected function createFormCollection(CodeAnchor $anchor = null): FormCollection
    {
        return new FormList(
            IdentifierAtom::fromString('test', $anchor),
            KeywordAtom::fromString('keyword'),
            StringAtom::fromString('string'),
            new Vector(NumberAtom::fromString('1'), NumberAtom::fromString('2'), NumberAtom::fromString('3')),
            new Map(new FormList(StringAtom::fromString('key'), StringAtom::fromString('value')))
        );
    }

    /**
     * @throws AssertionException
     * @throws Exception
     */
    public function testStringConvertible()
    {
        $this->assertEquals('()', (string)new FormList());
        $this->assertEquals(
            '(test 1 2 3)',
            (string)new FormList(
                IdentifierAtom::fromString('test'),
                NumberAtom::fromString('1'),
                NumberAtom::fromString('2'),
                NumberAtom::fromString('3')
            )
        );
    }

    /**
     * @throws AssertionException
     * @throws Exception
     * @throws ScopeException
     */
    public function testEvaluation()
    {
        $list = $this->createFormCollection();
        $scope = new Scope();
        $scope->define(IdentifierAtom::assertStaticType($list->assertHead())->getValue(), $this);
        $this->assertTrue($list->getTail()->equals(new FormList(...$scope->execute($list))));

        $list = $this->createFormCollection();
        $scope = new Scope();
        $scope->define(
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
            $scope->execute($list)
        );
    }

    /**
     * @throws AssertionException
     * @throws Exception
     */
    public function testNoHead()
    {
        $list = new FormList();
        $this->assertEmpty($list->all());
        $this->assertCount(0, $list);
        $this->assertTrue($list->isEmpty());
        $this->assertNull($list->getHead());
        $this->assertTrue($list->equals($list->getTail()));
        $this->assertNull((new Scope())->execute($list));

        $this->expectException(AssertionException::class);
        $list->assertHead();
    }

    /**
     * @throws AssertionException
     * @throws Exception
     */
    public function testEmptyTail()
    {
        $form = NumberAtom::fromString('42');
        $list = new FormList($form);
        $this->assertEmpty($list->getTail()->all());
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());
        $this->assertTrue($list->getTail()->isEmpty());
        $this->assertNotNull($list->getHead());
        $this->assertTrue($list->getHead()->equals($form));

        $this->expectException(AssertionException::class);
        $list->assertTailHead();
    }

    /**
     * @throws AssertionException
     * @throws Exception
     */
    public function testAsList()
    {
        $list = new FormList();
        $this->assertEquals($list, FormList::asList($list));

        $identifier = IdentifierAtom::fromString('identifier');
        $this->assertTrue(FormList::asList($identifier)->equals(new FormList($identifier)));
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testNotCallable()
    {
        $list = new FormList(IdentifierAtom::fromString('not-callable'));
        $scope = new Scope();
        $scope->define('not-callable', "This is a callable");

        $this->expectException(AssertionException::class);
        $scope->execute($list);
    }

    public function __invoke(ScopeContract $scope, FormContract ...$forms): array
    {
        return $forms;
    }

    /**
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testFailedEvaluation()
    {
        $scope = new Scope();
        $scope->define(
            'fail',
            function () {
                throw new Exception('Fail', 666);
            }
        );
        $list = new FormList(IdentifierAtom::fromString('fail'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Fail');
        $this->expectExceptionCode(666);
        $scope->execute($list);
    }

    /**
     * @throws AssertionException
     * @throws Exception
     */
    public function testMap()
    {
        $this->assertEquals(
            '((test) (42) (3.14) (#keyword))',
            (string)(new FormList(
                IdentifierAtom::fromString('test'),
                NumberAtom::fromString('42'),
                NumberAtom::fromString('3.14'),
                KeywordAtom::fromString('keyword')
            ))->map(
                function (FormContract $form) {
                    return FormList::asList($form);
                }
            )
        );
    }

    public function testUnpacking()
    {
        $list = new FormList(
            NumberAtom::fromString('3'),
            NumberAtom::fromString('4'),
            NumberAtom::fromString('5')
        );

        $array = [];
        array_push($array, ...$list);

        $this->assertEquals($array, $list->all());
    }
}

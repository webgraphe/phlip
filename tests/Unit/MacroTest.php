<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Scope;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\MarkedForm\QuasiquotedForm;
use Webgraphe\Phlip\MarkedForm\UnquotedForm;
use Webgraphe\Phlip\Tests\TestCase;

class MacroTest extends TestCase
{
    /**
     * @return Scope
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testSingleMacroExpansion(): Scope
    {
        $scope = new Scope();
        $square = $this->defineSquareMacro($scope);

        $this->assertTrue(
            $this->getExpectedSquareExpansion(3)->equals(
                $square->expand(new FormList(NumberAtom::fromString('3')))
            )
        );

        return $scope;
    }

    /**
     * @depends testSingleMacroExpansion
     * @param ScopeContract $scope
     * @throws AssertionException
     * @throws ScopeException
     */
    public function testNestedMacroExpansion(ScopeContract $scope)
    {
        $pythagoras = $this->definePythagorasMacro($scope);

        $this->assertTrue(
            $this->getExpectedPythagorasExpansion(3, 4)->equals(
                $pythagoras->expand(
                    new FormList(
                        NumberAtom::fromString('3'),
                        NumberAtom::fromString('4')
                    )
                )
            )
        );
    }

    /**
     * @param ScopeContract $scope
     * @return Macro
     * @throws AssertionException
     * @throws ScopeException
     */
    private function defineSquareMacro(ScopeContract $scope): Macro
    {
        $this->assertFalse($scope->has('square'), 'square macro already defined');

        $a = IdentifierAtom::fromString('a');
        $scope->define(
            'square',
            // (macro square (a) `(* ,a ,a))
            new Macro(
                $scope,
                new FormList($a),
                new QuasiquotedForm(
                    new FormList(
                        IdentifierAtom::fromString('*'),
                        new UnquotedForm($a),
                        new UnquotedForm($a)
                    )
                )
            )
        );

        return $scope->get('square');
    }

    /**
     * @param ScopeContract $scope
     * @return Macro
     * @throws AssertionException
     * @throws ScopeException
     */
    private function definePythagorasMacro(ScopeContract $scope): Macro
    {
        $this->assertFalse($scope->has('pythagoras'), 'square macro already defined');

        $a = IdentifierAtom::fromString('a');
        $b = IdentifierAtom::fromString('b');
        $scope->define(
            'pythagoras',
            new Macro(
                $scope,
                new FormList($a, $b),
                // (macro pythagoras (a b) `(sqrt (+ (square ,a) (square ,b))))
                new QuasiquotedForm(
                    new FormList(
                        IdentifierAtom::fromString('square-root'),
                        new FormList(
                            IdentifierAtom::fromString('+'),
                            new FormList(IdentifierAtom::fromString('square'), new UnquotedForm($a)),
                            new FormList(IdentifierAtom::fromString('square'), new UnquotedForm($b))
                        )
                    )
                )
            )
        );

        return $scope->get('pythagoras');
    }

    /**
     * @param int $a
     * @param int $b
     * @return FormList
     * @throws AssertionException
     */
    private function getExpectedPythagorasExpansion(int $a, int $b): FormList
    {
        $atomA = NumberAtom::fromString((string)$a);
        $atomB = NumberAtom::fromString((string)$b);

        // (square-root (+ (* a a) (* b b)))
        return new FormList(
            IdentifierAtom::fromString('square-root'),
            new FormList(
                IdentifierAtom::fromString('+'),
                new FormList(IdentifierAtom::fromString('*'), $atomA, $atomA),
                new FormList(IdentifierAtom::fromString('*'), $atomB, $atomB)
            )
        );
    }

    /**
     * @param int $a
     * @return FormList
     * @throws AssertionException
     */
    private function getExpectedSquareExpansion(int $a): FormList
    {
        $atomA = NumberAtom::fromString((string)$a);

        // (* a a)
        return new FormList(IdentifierAtom::fromString('*'), $atomA, $atomA);
    }
}

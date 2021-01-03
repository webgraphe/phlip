<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Macro;
use Webgraphe\Phlip\MarkedForm\QuasiquotedForm;
use Webgraphe\Phlip\MarkedForm\UnquotedForm;
use Webgraphe\Phlip\Tests\TestCase;

class MacroTest extends TestCase
{
    /**
     * @return Context
     * @throws AssertionException
     * @throws ContextException
     */
    public function testSingleMacroExpansion(): Context
    {
        $context = new Context();
        $square = $this->defineSquareMacro($context);

        $this->assertTrue(
            $this->getExpectedSquareExpansion(3)->equals(
                $square->expand(new FormList(NumberAtom::fromString('3')))
            )
        );

        return $context;
    }

    /**
     * @depends testSingleMacroExpansion
     * @param ContextContract $context
     * @throws AssertionException
     * @throws ContextException
     */
    public function testNestedMacroExpansion(ContextContract $context)
    {
        $pythagoras = $this->definePythagorasMacro($context);

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
     * @param ContextContract $context
     * @return Macro
     * @throws AssertionException
     * @throws ContextException
     */
    private function defineSquareMacro(ContextContract $context): Macro
    {
        $this->assertFalse($context->has('square'), 'square macro already defined');

        $a = IdentifierAtom::fromString('a');
        $context->define(
            'square',
            // (macro square (a) `(* ,a ,a))
            new Macro(
                $context,
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

        return $context->get('square');
    }

    /**
     * @param ContextContract $context
     * @return Macro
     * @throws AssertionException
     * @throws ContextException
     */
    private function definePythagorasMacro(ContextContract $context): Macro
    {
        $this->assertFalse($context->has('pythagoras'), 'square macro already defined');

        $a = IdentifierAtom::fromString('a');
        $b = IdentifierAtom::fromString('b');
        $context->define(
            'pythagoras',
            new Macro(
                $context,
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

        return $context->get('pythagoras');
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

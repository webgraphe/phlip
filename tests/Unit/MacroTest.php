<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
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
        $context = new Context;
        $square = $this->defineSquareMacro($context);

        $this->assertTrue(
            $this->getExpectedSquareExpansion(3)->equals(
                $square->expand(new ProperList(NumberAtom::fromString('3')))
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
                    new ProperList(
                        IdentifierAtom::fromString('3'),
                        IdentifierAtom::fromString('4')
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
                new ProperList($a),
                new QuasiquotedForm(
                    new ProperList(
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
                new ProperList($a, $b),
                // (macro pythagoras (a b) `(sqrt (+ (square ,a) (square ,b))))
                new QuasiquotedForm(
                    new ProperList(
                        IdentifierAtom::fromString('square-root'),
                        new ProperList(
                            IdentifierAtom::fromString('+'),
                            new ProperList(IdentifierAtom::fromString('square'), new UnquotedForm($a)),
                            new ProperList(IdentifierAtom::fromString('square'), new UnquotedForm($b))
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
     * @return ProperList
     * @throws AssertionException
     */
    private function getExpectedPythagorasExpansion(int $a, int $b): ProperList
    {
        $atomA = NumberAtom::fromString((string)$a);
        $atomB = NumberAtom::fromString((string)$b);

        // (square-root (+ (* a a) (* b b)))
        return new ProperList(
            IdentifierAtom::fromString('square-root'),
            new ProperList(
                IdentifierAtom::fromString('+'),
                new ProperList(IdentifierAtom::fromString('*'), $atomA, $atomA),
                new ProperList(IdentifierAtom::fromString('*'), $atomB, $atomB)
            )
        );
    }

    /**
     * @param int $a
     * @return ProperList
     * @throws AssertionException
     */
    private function getExpectedSquareExpansion(int $a): ProperList
    {
        $atomA = NumberAtom::fromString((string)$a);

        // (* a a)
        return new ProperList(IdentifierAtom::fromString('*'), $atomA, $atomA);
    }
}

<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Parser;
use Webgraphe\Phlip\MarkedForm\QuotedForm;

class QuotedFormTest extends TestCase
{
    /**
     * @throws LexerException
     * @throws ParserException
     */
    public function testQuotedNumberAtom()
    {
        $source = "'123";
        /** @var QuotedForm $quotedExpression */
        $quotedExpression = (new Parser())->parseLexemeStream((new Lexer())->parseSource($source))->getHead();
        $this->assertInstanceOf(QuotedForm::class, $quotedExpression);
        $this->assertEquals($source, (string)$quotedExpression);
        /** @var NumberAtom $expression */
        $expression = $quotedExpression->getForm();
        $this->assertInstanceOf(NumberAtom::class, $expression);
        $this->assertEquals(123, $expression->getValue());
    }
}

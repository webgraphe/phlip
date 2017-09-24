<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Parser;
use Webgraphe\Phlip\QuotedExpression;

class QuotedExpressionTest extends TestCase
{
    public function testQuotedNumberAtom()
    {
        $source = "'123";
        /** @var QuotedExpression $quotedExpression */
        $quotedExpression = (new Parser)->parseLexemeStream((new Lexer)->parseSource($source))->getHeadExpression();
        $this->assertInstanceOf(QuotedExpression::class, $quotedExpression);
        $this->assertEquals($source, (string)$quotedExpression);
        /** @var NumberAtom $expression */
        $expression = $quotedExpression->getExpression();
        $this->assertInstanceOf(NumberAtom::class, $expression);
        $this->assertEquals(123, $expression->getValue());
    }
}

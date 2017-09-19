<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
use Webgraphe\Phlip\Atom\LiteralAtom;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Parser;
use Webgraphe\Phlip\QuotedExpression;

class QuotedExpressionTest extends TestCase
{
    public function testQuotedLiteralNumber()
    {
        $source = "'123";
        /** @var QuotedExpression $quotedExpression */
        $quotedExpression = (new Parser)->parseLexemeStream((new Lexer)->parseSource($source))->getHeadExpression();
        $this->assertInstanceOf(QuotedExpression::class, $quotedExpression);
        $this->assertEquals($source, (string)$quotedExpression);
        /** @var LiteralAtom $expression */
        $expression = $quotedExpression->getExpression();
        $this->assertInstanceOf(LiteralAtom::class, $expression);
        $this->assertTrue($expression->isNumber());
        $this->assertEquals(123, $expression->getNumberValue());
    }
}

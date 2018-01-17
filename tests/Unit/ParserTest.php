<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Parser;

class ParserTest extends TestCase
{
    public function testIncoherentStatement()
    {
        $this->expectException(ParserException::class);
        (new Parser)->parseLexemeStream((new Lexer)->parseSource(')'));
    }

    public function testStringConvertibleList()
    {
        $source = '(identifier1 "string" `(identifier2 \'x ~y 42 3.14 [a b] {#key "value"}))';
        $this->assertEquals("($source)", (string)(new Parser)->parseLexemeStream((new Lexer)->parseSource($source)));
    }

    public function testFailedParse()
    {
        $this->expectException(ParserException::class);
        (new Parser)->parseLexemeStream(LexemeStream::fromLexemes(OpenListSymbol::instance()));
    }

    public function testMalformedPairMissingLeftHandSide()
    {
        $this->expectException(ParserException::class);
        (new Parser)->parseLexemeStream((new Lexer)->parseSource("(. 2)"));
    }

    public function testMalformedPairMissingRightHandSide()
    {
        $this->expectException(ParserException::class);
        (new Parser)->parseLexemeStream((new Lexer)->parseSource("(2 .)"));
    }

    public function testMalformedPairTooManyFormsOnRightHandSide()
    {
        $this->expectException(ParserException::class);
        (new Parser)->parseLexemeStream((new Lexer)->parseSource("(1 . 2 3)"));
    }

    public function testValidPair()
    {
        $this->assertEquals(
            '((1 . 2))',
            (string)(new Parser)->parseLexemeStream((new Lexer)->parseSource("(1 . 2)"))
        );
    }

    public function testValidNestedPairs()
    {
        $this->assertEquals(
            '((1 2 . 3))',
            (string)(new Parser)->parseLexemeStream((new Lexer)->parseSource("(1 . (2 . 3))"))
        );
    }

    public function testValidPairWithEndingProperList()
    {
        $this->assertEquals(
            '((1 2 3))',
            (string)(new Parser)->parseLexemeStream((new Lexer)->parseSource("(1 . (2 3))"))
        );
    }
}

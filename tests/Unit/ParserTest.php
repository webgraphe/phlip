<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Parser;

class ParserTest extends TestCase
{
    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testIncoherentStatement()
    {
        $this->expectException(ParserException::class);
        (new Parser())->parseLexemeStream((new Lexer())->parseSource(')'));
    }

    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testStringConvertibleList()
    {
        $source = '(identifier1 "string" `(identifier2 \'x ,y 42 3.14 [a b] {#key "value"}))';
        $this->assertEquals(
            "($source)",
            (string)(new Parser())->parseLexemeStream((new Lexer())->parseSource($source))
        );
    }

    /**
     * @throws ParserException
     */
    public function testFailedParse()
    {
        $this->expectException(ParserException::class);
        (new Parser())->parseLexemeStream(LexemeStream::fromLexemes(OpenListSymbol::instance()));
    }

    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testMalformedPairMissingLeftHandSide()
    {
        $this->expectException(ParserException::class);
        (new Parser())->parseLexemeStream((new Lexer())->parseSource("(. 2)"));
    }

    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testMalformedPairMissingRightHandSide()
    {
        $this->expectException(ParserException::class);
        (new Parser())->parseLexemeStream((new Lexer())->parseSource("(2 .)"));
    }

    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testMalformedPairTooManyFormsOnRightHandSide()
    {
        $this->expectException(ParserException::class);
        (new Parser())->parseLexemeStream((new Lexer())->parseSource("(1 . 2 3)"));
    }

    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testValidPair()
    {
        $this->assertEquals(
            '((1 . 2))',
            (string)(new Parser())->parseLexemeStream((new Lexer())->parseSource("(1 . 2)"))
        );
    }

    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testValidNestedPairs()
    {
        $this->assertEquals(
            '((1 2 . 3))',
            (string)(new Parser())->parseLexemeStream((new Lexer())->parseSource("(1 . (2 . 3))"))
        );
    }

    /**
     * @throws ParserException
     * @throws LexerException
     */
    public function testValidPairWithEndingProperList()
    {
        $this->assertEquals(
            '((1 2 3))',
            (string)(new Parser())->parseLexemeStream((new Lexer())->parseSource("(1 . (2 3))"))
        );
    }

    /**
     * @throws LexerException
     */
    public function testInvalidMap()
    {
        try {
            (new Parser())->parseLexemeStream((new Lexer())->parseSource("{#a 1 #b}"));
            $this->fail("Expected ParserException for malformed map");
        } catch (ParserException $e) {
            $this->assertInstanceOf(AssertionException::class, $previous = $e->getPrevious());
            $this->assertEquals("Malformed map; non-even number of key-value items", $previous->getMessage());
        }
    }
}

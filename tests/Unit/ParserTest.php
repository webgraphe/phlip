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
        $source = '(identifier1 "string" (identifier2 \'x 42 3.14))';
        $this->assertEquals("($source)", (string)(new Parser)->parseLexemeStream((new Lexer)->parseSource($source)));
    }

    public function testFailedParse()
    {
        $this->expectException(ParserException::class);
        (new Parser)->parseLexemeStream(LexemeStream::fromLexemes(OpenListSymbol::instance()));
    }
}

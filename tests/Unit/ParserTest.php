<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Tests\Webgraphe\Phlip\TestCase;
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
}

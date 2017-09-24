<?php

namespace Tests\Webgraphe\Phlip\Unit;

use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Atom\BooleanAtom;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\NullAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Comment;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Symbol;

class LexerTest extends TestCase
{
    public function testParse()
    {
        $lexer = new Lexer;
        $source = <<<SOURCE
; A comment
(identifier1 "string" (identifier2 'x 42 3.14 true false null [1 2 3]))
SOURCE;
        $lexemeStream = $lexer->parseSource($source);
        $this->assertNotNull($lexemeStream);

        $expectedTokens = [
            new Comment('A comment'),
            Symbol\Opening\OpenListSymbol::instance(),
            new IdentifierAtom('identifier1'),
            new StringAtom('string'),
            Symbol\Opening\OpenListSymbol::instance(),
            new IdentifierAtom('identifier2'),
            Symbol\QuoteSymbol::instance(),
            new IdentifierAtom('x'),
            new NumberAtom('42'),
            new NumberAtom('3.14'),
            BooleanAtom::true(),
            BooleanAtom::false(),
            NullAtom::instance(),
            Symbol\Opening\OpenArraySymbol::instance(),
            new NumberAtom('1'),
            new NumberAtom('2'),
            new NumberAtom('3'),
            Symbol\Closing\CloseArraySymbol::instance(),
            Symbol\Closing\CloseListSymbol::instance(),
            Symbol\Closing\CloseListSymbol::instance(),
        ];

        $this->assertCount(count($expectedTokens), $lexemeStream);
        $i = 0;
        while ($lexemeStream->valid()) {
            $lexeme = $lexemeStream->current();
            $this->assertInstanceOf(get_class($expectedTokens[$i]), $lexeme);
            $this->assertEquals((string)$expectedTokens[$i], (string)$lexeme);
            $lexemeStream->next();
            ++$i;
        }
    }

    public function testUnexpectedEndOfString()
    {
        $lexer = new Lexer;

        $this->expectException(\RuntimeException::class);
        $lexer->parseSource('"non-terminated string');
    }

    public function testUnexpectedEndOfEscapedString()
    {
        $lexer = new Lexer;

        $this->expectException(\RuntimeException::class);
        $lexer->parseSource('"non-terminated escaped string\\');
    }
}

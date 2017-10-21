<?php

namespace Tests\Webgraphe\Phlip\Unit;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Symbol;
use Webgraphe\Phlip\Tests\TestCase;

class LexerTest extends TestCase
{
    public function testParse()
    {
        $lexer = new Lexer;
        $source = <<<SOURCE
; A comment
(identifier1 "string" (identifier2 'x `(+ ~x ~y) 42 3.14 :keyword (key . value) [1 2 3] . {(key value)}))
SOURCE;
        $lexemeStream = $lexer->parseSource($source);
        $this->assertNotNull($lexemeStream);

        $expectedTokens = [
            Symbol\Opening\OpenListSymbol::instance(),
            IdentifierAtom::fromString('identifier1'),
            StringAtom::fromString('string'),
            Symbol\Opening\OpenListSymbol::instance(),
            IdentifierAtom::fromString('identifier2'),
            Symbol\Mark\StraightSingleMarkSymbol::instance(),
            IdentifierAtom::fromString('x'),
            Symbol\Mark\GraveAccentSymbol::instance(),
            Symbol\Opening\OpenListSymbol::instance(),
            IdentifierAtom::fromString('+'),
            Symbol\Mark\TildeSymbol::instance(),
            IdentifierAtom::fromString('x'),
            Symbol\Mark\TildeSymbol::instance(),
            IdentifierAtom::fromString('y'),
            Symbol\Closing\CloseListSymbol::instance(),
            NumberAtom::fromString('42'),
            NumberAtom::fromString('3.14'),
            KeywordAtom::fromString('keyword'),
            Symbol\Opening\OpenListSymbol::instance(),
            IdentifierAtom::fromString('key'),
            Symbol\DotSymbol::instance(),
            IdentifierAtom::fromString('value'),
            Symbol\Closing\CloseListSymbol::instance(),
            Symbol\Opening\OpenVectorSymbol::instance(),
            NumberAtom::fromString('1'),
            NumberAtom::fromString('2'),
            NumberAtom::fromString('3'),
            Symbol\Closing\CloseVectorSymbol::instance(),
            Symbol\DotSymbol::instance(),
            Symbol\Opening\OpenMapSymbol::instance(),
            Symbol\Opening\OpenListSymbol::instance(),
            IdentifierAtom::fromString('key'),
            IdentifierAtom::fromString('value'),
            Symbol\Closing\CloseListSymbol::instance(),
            Symbol\Closing\CloseMapSymbol::instance(),
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

        $this->expectException(LexerException::class);
        $lexer->parseSource('"non-terminated string');
    }

    public function testUnexpectedEndOfEscapedString()
    {
        $lexer = new Lexer;

        $this->expectException(LexerException::class);
        $lexer->parseSource('"non-terminated escaped string\\');
    }
}

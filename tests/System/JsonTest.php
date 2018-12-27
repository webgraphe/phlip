<?php

namespace Webgraphe\Phlip\Tests\System;

use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class JsonTest extends TestCase
{
    private static function getJsonFilePath()
    {
        return dirname(__DIR__) . '/Data/Json/phlip.json';
    }

    /**
     * @throws \Webgraphe\Phlip\Exception\ContextException
     * @throws \Webgraphe\Phlip\Exception\IOException
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     * @throws \Exception
     */
    public function testJson()
    {
        $init = Program::parseFile(self::getJsonFilePath());
        $context = new Context;
        $context->define('null', null);
        $context->define('true', true);
        $context->define('false', false);
        $data = $init->execute($context);
        $jsonDecodedData = json_decode(file_get_contents(self::getJsonFilePath()));
        $this->assertEquals($data, $jsonDecodedData);
    }

    /**
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Exception
     */
    public function testJsonAlike()
    {
        $lexer = new Lexer;
        $lexemeStream = $lexer->parseSource(file_get_contents(self::getJsonFilePath()))->jsonAlike();
        $this->assertJsonStringEqualsJsonFile(self::getJsonFilePath(), (string)$lexemeStream);
    }
}

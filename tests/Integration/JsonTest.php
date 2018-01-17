<?php

namespace Webgraphe\Phlip\Tests\Integration;

use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class JsonTest extends TestCase
{
    private static function getJsonFilePath()
    {
        return file_get_contents(__DIR__ . '/Data/menu.json');
    }

    /**
     * @throws \Webgraphe\Phlip\Exception
     * @throws \Webgraphe\Phlip\Exception\EvaluationException
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     */
    public function testJson()
    {
        $init = Program::parse(self::getJsonFilePath());
        $context = new Context;
        $context->define('null', null);
        $data = $init->execute($context);
        $jsonDecodedData = json_decode(self::getJsonFilePath());
        $this->assertEquals($data, $jsonDecodedData);
    }
}

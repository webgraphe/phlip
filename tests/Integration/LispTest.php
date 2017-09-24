<?php

namespace Webgraphe\Phlip\Tests\Integration;

use Webgraphe\Phlip\Tests\TestCase;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Program;

class LispTest extends TestCase
{
    private static function getScript()
    {
        return file_get_contents(__DIR__ . "/Scripts/Lisp.phlip");
    }

    /**
     * @dataProvider scripts
     * @param $script
     */
    public function testHomoiconicity($script)
    {
        $context = Context\PhlipyContext::withLispPrimitives(new Context);
        $init = Program::parse(self::getScript());
        $init->execute($context);

        $this->assertEquals(
            Program::parse($script)->execute($context),
            Program::parse("(eval (quote $script) (quote ()))")->execute($context)
        );
    }

    /**
     * @todo Add more test to prove homoiconicity further more.
     * @return array
     */
    public function scripts()
    {
        return [
            "'foo'" => [
                'script' => '(quote foo)'
            ],
            "'(foo bar baz)" => [
                'script' => '(cons (quote foo) (quote (bar baz)))'
            ]
        ];
    }
}

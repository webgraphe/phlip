<?php

namespace Webgraphe\Phlip\Tests\Integration;

use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class LispTest extends TestCase
{
    private static function getScript(): ?string
    {
        return file_get_contents(__DIR__ . "/Scripts/Lisp.phlip") ?: null;
    }

    /**
     * @dataProvider scripts
     * @param $script
     * @throws \Webgraphe\Phlip\Exception\LexerException
     * @throws \Webgraphe\Phlip\Exception\ParserException
     */
    public function testHomoiconicity($script)
    {
        $context = Phlipy::withBasicLanguageConstructs(new Context);
        $init = Program::parse(self::getScript());
        $init->execute($context);

        /** @var FormContract $left */
        $left = Program::parse($script)->execute($context);
        $this->assertInstanceOf(FormContract::class, $left);
        /** @var FormContract $right */
        $right = Program::parse("(eval (quote $script) (quote ()))")->execute($context);
        $this->assertInstanceOf(FormContract::class, $right);
        $this->assertTrue($left->equals($right));
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

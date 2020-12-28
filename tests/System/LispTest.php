<?php

namespace Webgraphe\Phlip\Tests\System;

use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

/**
 * This test is an attempt at reproducing McCarthy's eval.
 *
 * @link http://www.paulgraham.com/rootsoflisp.html
 */
class LispTest extends TestCase
{
    protected static function getScript(): ?string
    {
        return file_get_contents(dirname(__DIR__) . "/Data/Lisp/Lisp.phlip") ?: null;
    }

    /**
     * @dataProvider scripts
     * @param string $script
     * @throws ContextException
     * @throws LexerException
     * @throws ParserException
     * @throws AssertionException
     * @throws ProgramException
     */
    public function testMcCarthyEval(string $script)
    {
        $context = Phlipy::roots(new Context())->getContext();
        $init = Program::parse(static::getScript());
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
     * @return array
     */
    public function scripts(): array
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

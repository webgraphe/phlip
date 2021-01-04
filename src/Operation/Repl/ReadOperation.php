<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Closure;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Operation\ManualOperation;
use Webgraphe\Phlip\Parser;

/**
 * Accepts an expression from the input and parses it into a ProperList of statements that are shifted until the
 * list is empty, which makes this operation behave like a generator as it will shift statements from the last input
 * parsed until the resulting ProperList is empty.
 */
class ReadOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'read';

    /** @var Closure */
    private $prompt;
    /** @var bool */
    private $multiLine = false;
    /** @var Lexer */
    private $lexer;
    /** @var Parser */
    private $parser;
    /** @var FormList|null */
    private $result;

    public function __construct(Lexer $lexer = null, Parser $parser = null, Closure $prompt = null)
    {
        $this->lexer = $lexer ?? new Lexer();
        $this->parser = $parser ?? new Parser();
        $this->prompt = $prompt ?? static::readPrompt();
    }

    protected static function readPrompt(): Closure
    {
        return function (ScopeContract $scope) {
            static $lastTicks;
            $ticks = null === $lastTicks
                ? 0
                : max(0, $scope->getTicks() - $lastTicks - 6);
            $lastTicks = $scope->getTicks();

            return sprintf('[%d] >>> ', $ticks);
        };
    }

    /**
     * @param Lexer|null $lexer
     * @param Parser|null $parser
     * @param Closure|null $prompt
     * @return static
     */
    public static function multiLine(Lexer $lexer = null, Parser $parser = null, Closure $prompt = null): self
    {
        $self = new static($lexer, $parser, $prompt);
        $self->multiLine = true;

        return $self;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @return bool
     */
    public function isMultiLine(): bool
    {
        return $this->multiLine;
    }

    /**
     * @return Closure
     */
    public function getPrompt(): Closure
    {
        return $this->prompt;
    }

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return FormContract
     * @throws AssertionException
     * @throws LexerException
     * @throws ParserException
     */
    protected function invoke(ScopeContract $scope, FormList $forms): FormContract
    {
        if ($this->result && ($head = $this->result->getHead())) {
            $this->result = $this->result->getTail();

            return $head;
        }

        $lines = [];
        while (true) {
            $prompt = $lines ? '' : $this->prompt;
            $line = rtrim(
                readline(
                    is_callable($prompt)
                        ? call_user_func($prompt, $scope)
                        : $prompt
                )
            );
            $break = $this->multiLine && !$line
                || !$this->multiLine && $line && '\\' !== $line[strlen($line) - 1];
            if ($line) {
                $lines[] = rtrim($line, '\\');
            }
            if ($break) {
                if (!$lines) {
                    return $this->invoke($scope, $forms);
                }
                break;
            }
        }

        if ($return = trim(implode(PHP_EOL, $lines))) {
            readline_add_history($return);
        }

        $this->result = $this->parser->parseLexemeStream($this->lexer->parseSource(implode(PHP_EOL, $lines)));
        $head = $this->result->assertHead();
        $this->result = $this->result->getTail();

        return $head;
    }
}

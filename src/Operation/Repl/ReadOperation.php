<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Closure;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class ReadOperation extends PrimaryOperation
{
    /** @var string */
    const IDENTIFIER = 'read';

    /** @var Closure */
    private $prompt;
    /** @var bool */
    private $multiLine = false;

    public function __construct(Closure $prompt = null)
    {
        $this->prompt = $prompt ?? self::readPrompt();
    }

    protected static function readPrompt(): Closure
    {
        return function (ContextContract $context) {
            static $lastTicks;
            $ticks = null === $lastTicks
                ? 0
                : max(0, $context->getTicks() - $lastTicks - 6);
            $lastTicks = $context->getTicks();

            return sprintf('[%d] >>> ', $ticks);
        };
    }

    /**
     * @param Closure|null $prompt
     * @return static
     */
    public static function multiLine(Closure $prompt = null): self
    {
        $self = new static($prompt);
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
     * @param ContextContract $context
     * @param ProperList $forms
     * @return string
     */
    protected function invoke(ContextContract $context, ProperList $forms): string
    {
        $lines = [];
        while (true) {
            $prompt = $lines ? '' : $this->prompt;
            $line = rtrim(
                readline(
                    is_callable($prompt)
                        ? call_user_func($prompt, $context)
                        : $prompt
                )
            );
            $break = $this->multiLine && !$line
                || !$this->multiLine && '\\' !== $line[strlen($line) - 1];
            if ($line) {
                $lines[] = rtrim($line, '\\');
            }
            if ($break) {
                if (!$lines) {
                    return $this->invoke($context, $forms);
                }
                break;
            }
        }

        if ($return = trim(implode(PHP_EOL, $lines))) {
            readline_add_history($return);
        }

        return implode(PHP_EOL, $lines);
    }
}

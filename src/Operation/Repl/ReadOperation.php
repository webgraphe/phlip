<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Webgraphe\Phlip\Operation\StandardOperation;

class ReadOperation extends StandardOperation
{
    const IDENTIFIER = 'read';

    /** @var string|callable */
    private $prompt;
    private bool $multiLine = false;

    public function __construct($prompt = null)
    {
        $this->prompt = $prompt;
    }

    /**
     * @param string|callable|null $prompt
     * @return static
     */
    public static function multiLine($prompt = null)
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
     * @param array ...$arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $lines = [];
        while (true) {
            $prompt = $lines ? '' : $this->prompt;
            $line = rtrim(
                readline(
                    is_callable($prompt)
                        ? call_user_func($prompt)
                        : $prompt
                )
            );
            $break = $this->multiLine && !$line
                || !$this->multiLine && $line && '\\' !== $line[strlen($line) - 1];
            $lines[] = rtrim($line, '\\');
            if ($break) {
                break;
            }
        }

        if ($return = trim(implode(PHP_EOL, $lines))) {
            readline_add_history($return);
        }

        return implode(PHP_EOL, $lines);
    }
}

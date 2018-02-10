<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Webgraphe\Phlip\Operation\StandardOperation;

class ReadOperation extends StandardOperation
{
    const IDENTIFIER = 'read';

    /** @var string|callable */
    private $prompt;

    public function __construct($prompt = null)
    {
        $this->prompt = $prompt;
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
            $break = !$line || '\\' !== $line[strlen($line) - 1];
            $lines[] = rtrim($line, '\\');
            if ($break) {
                break;
            }
        }

        return implode(PHP_EOL, $lines);
    }
}

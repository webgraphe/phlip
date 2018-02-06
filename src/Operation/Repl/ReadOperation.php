<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Webgraphe\Phlip\Operation\StandardOperation;

class ReadOperation extends StandardOperation
{
    const IDENTIFIER = 'read';

    /** @var string */
    private $prompt;

    public function __construct(string $prompt = null)
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
            $line = rtrim(readline($lines ? '' : $this->prompt));
            $break = !$line || '\\' !== $line[strlen($line) - 1];
            $lines[] = rtrim($line, '\\');
            if ($break) {
                break;
            }
        }

        return implode(PHP_EOL, $lines);
    }
}

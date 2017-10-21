<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormCollection\ProperList;

class Program
{
    /** @var FormContract[] */
    private $statements;

    public function __construct(ProperList $statements)
    {
        $this->statements = $statements->all();
    }

    public static function parse(string $code, string $name = null, Lexer $lexer = null, Parser $parser = null): Program
    {
        $lexer = $lexer ?? new Lexer;
        $parser = $parser ?? new Parser;

        return new self($parser->parseLexemeStream($lexer->parseSource($code, $name)));
    }

    public static function parseFile(string $path, Lexer $lexer = null, Parser $parser = null): Program
    {
        if (!file_exists($path)) {
            throw new ProgramException("Not a file");
        }
        if (!is_readable($path)) {
            throw new ProgramException("File not readable");
        }

        return static::parse(file_get_contents($path), $lexer, $parser);
    }

    /**
     * @param ContextContract $context
     * @param WalkerContract|null $walker
     * @return mixed
     */
    public function execute(ContextContract $context, WalkerContract $walker = null)
    {
        $walker = $walker ?? new Walker($context);

        $result = null;
        foreach ($this->statements as $statement) {
            $result = $walker($statement)->evaluate($context);
        }

        return $result;
    }
}

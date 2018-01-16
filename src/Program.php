<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Exception\ProgramException;

class Program
{
    /** @var Contracts\FormContract[] */
    private $statements;

    public function __construct(FormCollection\ProperList $statements)
    {
        $this->statements = $statements->all();
    }

    /**
     * @param string $code
     * @param string|null $name
     * @param Lexer|null $lexer
     * @param Parser|null $parser
     * @return Program
     * @throws Exception
     * @throws LexerException
     * @throws ParserException
     */
    public static function parse(string $code, string $name = null, Lexer $lexer = null, Parser $parser = null): Program
    {
        $lexer = $lexer ?? new Lexer;
        $parser = $parser ?? new Parser;

        return new self($parser->parseLexemeStream($lexer->parseSource($code, $name)));
    }

    /**
     * @param string $path
     * @param Lexer|null $lexer
     * @param Parser|null $parser
     * @return Program
     * @throws Exception
     * @throws LexerException
     * @throws ParserException
     * @throws ProgramException
     */
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
     * @param Contracts\ContextContract $context
     * @param Contracts\WalkerContract|null $walker
     * @param array $arguments
     * @return mixed
     * @throws EvaluationException
     */
    public function execute(
        Contracts\ContextContract $context,
        Contracts\WalkerContract $walker = null,
        array $arguments = []
    ) {
        $walker = $walker ?? new Walker($context);

        if ($arguments) {
            $context = $context->stack();
            foreach ($arguments as $key => $value) {
                $context->let('$' . $key, $value);
            }
        }

        $result = null;
        try {
            foreach ($this->statements as $statement) {
                $result = $context->execute($walker($statement));
            }
        } catch (\Throwable $t) {
            throw EvaluationException::fromContext(clone $context, 'Program execution failed', 0, $t);
        }

        return $result;
    }
}

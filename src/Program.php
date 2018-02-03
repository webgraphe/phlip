<?php

namespace Webgraphe\Phlip;

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
     * @throws Exception\LexerException
     * @throws Exception\ParserException
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
     * @throws Exception\LexerException
     * @throws Exception\ParserException
     * @throws Exception\IOException
     */
    public static function parseFile(string $path, Lexer $lexer = null, Parser $parser = null): Program
    {
        if (!file_exists($path)) {
            throw Exception\IOException::fromPath($path, "Not a file");
        }
        if (!is_readable($path)) {
            throw Exception\IOException::fromPath($path, "File not readable");
        }

        return static::parse(file_get_contents($path), $lexer, $parser);
    }

    /**
     * @param Contracts\ContextContract $context
     * @param Contracts\WalkerContract|null $walker
     * @param array $arguments
     * @return mixed
     * @throws Exception\ProgramException
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
            throw Exception\ProgramException::fromContext(clone $context, 'Program execution failed', 0, $t);
        }

        return $result;
    }
}

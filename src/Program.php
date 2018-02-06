<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\FormCollection\ProperList;

class Program
{
    /** @var ProperList */
    private $statements;

    public function __construct(ProperList $statements)
    {
        $this->statements = $statements;
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
     * @return ProperList
     */
    public function getStatements(): ProperList
    {
        return $this->statements;
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
     * @param array $arguments
     * @return mixed
     * @throws Exception\ProgramException
     */
    public function execute(Contracts\ContextContract $context, array $arguments = [])
    {
        if ($arguments) {
            $context = $context->stack();
            foreach ($arguments as $key => $value) {
                $context->let('$' . $key, $value);
            }
        }

        $result = null;
        try {
            $statements = $this->statements;
            while ($head = $statements->getHead()) {
                $statements = $statements->getTail();
                $result = $context->execute($head);
            }
        } catch (\Throwable $t) {
            throw Exception\ProgramException::fromContext(clone $context, 'Program execution failed', 0, $t);
        }

        return $result;
    }
}

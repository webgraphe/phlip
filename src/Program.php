<?php

namespace Webgraphe\Phlip;

use Closure;
use Throwable;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\Exception\ProgramException;
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
        $lexer = $lexer ?? new Lexer();
        $parser = $parser ?? new Parser();

        return new static($parser->parseLexemeStream($lexer->parseSource($code, $name)));
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

        return static::parse(file_get_contents($path), $path, $lexer, $parser);
    }

    /**
     * @param Contracts\ContextContract $context
     * @return Closure
     */
    public function compile(Contracts\ContextContract $context): Closure
    {
        return function (...$arguments) use ($context) {
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
            } catch (PhlipException $t) {
                throw $t;
            } catch (Throwable $t) {
                throw Exception\ProgramException::fromContext(clone $context, 'Program execution failed', 0, $t);
            }

            return $result;
        };
    }

    /**
     * @noinspection PhpDocRedundantThrowsInspection These exception
     * @param Contracts\ContextContract $context
     * @param mixed ...$arguments
     * @return mixed
     * @throws ProgramException
     * @throws AssertionException
     * @throws ContextException
     */
    public function execute(Contracts\ContextContract $context, ...$arguments)
    {
        return call_user_func($this->compile($context), ...$arguments);
    }
}

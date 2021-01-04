<?php

namespace Webgraphe\Phlip;

use Closure;
use Throwable;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormCollection\FormList;

class Program
{
    /** @var FormList */
    private $statements;

    public function __construct(FormList $statements)
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
     * @param Contracts\ScopeContract $scope
     * @return Closure
     */
    public function compile(Contracts\ScopeContract $scope): Closure
    {
        return function (...$arguments) use ($scope) {
            if ($arguments) {
                $scope = $scope->stack();
                foreach ($arguments as $key => $value) {
                    $scope->let('$' . $key, $value);
                }
            }

            $result = null;
            try {
                $statements = $this->statements;
                while ($head = $statements->getHead()) {
                    $statements = $statements->getTail();
                    $result = $scope->execute($head);
                }
            } catch (PhlipException $t) {
                throw $t;
            } catch (Throwable $t) {
                throw Exception\ProgramException::fromScope(clone $scope, 'Program execution failed', 0, $t);
            }

            return $result;
        };
    }

    /**
     * @noinspection PhpDocRedundantThrowsInspection These exception
     * @param Contracts\ScopeContract $scope
     * @param mixed ...$arguments
     * @return mixed
     * @throws ProgramException
     * @throws AssertionException
     * @throws ScopeException
     */
    public function execute(Contracts\ScopeContract $scope, ...$arguments)
    {
        return call_user_func($this->compile($scope), ...$arguments);
    }
}

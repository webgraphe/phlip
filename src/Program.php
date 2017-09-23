<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\ProgramException;

class Program
{
    /** @var ExpressionList */
    private $statements;

    public function __construct(ExpressionList $statements)
    {
        $this->statements = $statements;
    }

    public static function parse(string $code, Lexer $lexer = null, Parser $parser = null): Program
    {
        $lexer = $lexer ?? new Lexer;
        $parser = $parser ?? new Parser;

        return new self($parser->parseLexemeStream($lexer->parseSource($code)));
    }

    public static function parseFile(string $path, Lexer $lexer = null, Parser $parser = null): Program
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new ProgramException("'$path' is not a file or is not readable");
        }

        return static::parse(file_get_contents($path), $lexer, $parser);
    }

    /**
     * @param ContextContract $context
     * @return mixed
     */
    public function execute(ContextContract $context)
    {
        $result = null;

        $statements = $this->statements;
        while ($statement = $statements->getHeadExpression()) {
            $result = $statement->evaluate($context);
            $statements = $statements->getTailExpressions();
        }

        return $result;
    }

    /**
     * @return ExpressionList
     */
    public function getStatements(): ExpressionList
    {
        return $this->statements;
    }
}

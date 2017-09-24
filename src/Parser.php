<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\ArrayAtom;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Closing\CloseArraySymbol;
use Webgraphe\Phlip\Symbol\Closing\CloseListSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenArraySymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\QuoteSymbol;

class Parser
{
    public function parseLexemeStream(LexemeStream $stream): ExpressionList
    {
        /** @var ExpressionList[] $statements */
        $statements = [];
        while($stream->valid()) {
            if ($statement = $this->extractNextStatement($stream)) {
                $statements[] = $statement;
            }
        }

        return new ExpressionList(...$statements);
    }

    /**
     * @param LexemeStream $stream
     * @return ExpressionContract
     * @throws \Exception
     */
    private function extractNextStatement(LexemeStream $stream): ?ExpressionContract
    {
        $lexeme = $stream->current();
        $stream->next();

        if ($lexeme instanceof QuoteSymbol) {
            return new QuotedExpression($this->extractNextStatement($stream));
        }

        if ($lexeme instanceof OpenListSymbol) {
            return $this->extractExpressionList($stream);
        }

        if ($lexeme instanceof OpenArraySymbol) {
            return $this->extractArrayAtom($stream);
        }

        if ($lexeme instanceof Atom) {
            return $lexeme;
        }

        if ($lexeme instanceof Comment) {
            return null;
        }

        throw new ParserException("Unexpected lexeme '$lexeme'");
    }

    private function extractExpressionList(LexemeStream $stream): ExpressionList
    {
        return new ExpressionList(...$this->extractNextStatementsUntilSymbol($stream, CloseListSymbol::instance()));
    }

    private function extractArrayAtom(LexemeStream $stream): ArrayAtom
    {
        return new ArrayAtom(...$this->extractNextStatementsUntilSymbol($stream, CloseArraySymbol::instance()));
    }

    /**
     * @param LexemeStream $stream
     * @param Closing $symbol
     * @return ExpressionContract[]
     */
    private function extractNextStatementsUntilSymbol(LexemeStream $stream, Closing $symbol): array
    {
        $list = [];
        while ($stream->current() !== $symbol) {
            if ($statement = $this->extractNextStatement($stream)) {
                $list[] = $statement;
            }
        }
        $stream->next();

        return $list;
    }
}

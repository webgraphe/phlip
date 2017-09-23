<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\CloseListSymbol;
use Webgraphe\Phlip\Symbol\OpenListSymbol;
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
            $list = [];
            while (!($stream->current() instanceof CloseListSymbol)) {
                if ($statement = $this->extractNextStatement($stream)) {
                    $list[] = $statement;
                }
            }
            $stream->next();

            return new ExpressionList(...$list);
        }

        if ($lexeme instanceof Atom) {
            return $lexeme;
        }

        if ($lexeme instanceof Comment) {
            return null;
        }

        throw new ParserException("Unexpected lexeme '$lexeme'");
    }
}

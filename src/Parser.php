<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Symbol\CloseDelimiterSymbol;
use Webgraphe\Phlip\Symbol\OpenDelimiterSymbol;
use Webgraphe\Phlip\Symbol\QuoteSymbol;

class Parser
{
    public function parseLexemeStream(LexemeStream $stream): ExpressionList
    {
        /** @var ExpressionList[] $statements */
        $statements = [];
        while($stream->isValid()) {
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

        if ($lexeme instanceof OpenDelimiterSymbol) {
            $list = [];
            while (!($stream->current() instanceof CloseDelimiterSymbol)) {
                $list[] = $this->extractNextStatement($stream);
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

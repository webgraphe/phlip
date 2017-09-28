<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\ArrayAtom;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\KeywordSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenArraySymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\QuoteSymbol;

class Parser
{
    /**
     * @param LexemeStream $stream
     * @return FormList
     * @throws Exception
     * @throws ParserException
     */
    public function parseLexemeStream(LexemeStream $stream): FormList
    {
        /** @var FormList[] $statements */
        $statements = [];
        try {
            while ($stream->valid()) {
                if ($statement = $this->extractNextStatement($stream)) {
                    $statements[] = $statement;
                }
            }
        } catch (Exception $exception) {
            if ($exception instanceof ParserException) {
                throw $exception;
            }

            throw new ParserException('Failed parsing lexeme stream', 0, $exception);
        }

        return new FormList(...$statements);
    }

    /**
     * @param LexemeStream $stream
     * @return FormContract
     * @throws \Exception
     */
    private function extractNextStatement(LexemeStream $stream): ?FormContract
    {
        $lexeme = $stream->current();
        $stream->next();

        if ($lexeme instanceof QuoteSymbol) {
            return new QuotedForm($this->extractNextStatement($stream));
        }

        if ($lexeme instanceof KeywordSymbol) {
            return KeywordAtom::fromIdentifierAtom(
                IdentifierAtom::assertStaticType($this->extractNextStatement($stream))
            );
        }

        if ($lexeme instanceof OpenListSymbol) {
            return $this->extractFormList($stream);
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

    private function extractFormList(LexemeStream $stream): FormList
    {
        return new FormList(
            ...$this->extractNextStatementsUntilClosingSymbol(
                $stream,
                OpenListSymbol::instance()->getRelatedClosingSymbol()
            )
        );
    }

    private function extractArrayAtom(LexemeStream $stream): ArrayAtom
    {
        return new ArrayAtom(
            ...$this->extractNextStatementsUntilClosingSymbol(
                $stream,
                OpenArraySymbol::instance()->getRelatedClosingSymbol()
            )
        );
    }

    /**
     * @param LexemeStream $stream
     * @param Closing $symbol
     * @return FormContract[]
     */
    private function extractNextStatementsUntilClosingSymbol(LexemeStream $stream, Closing $symbol): array
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

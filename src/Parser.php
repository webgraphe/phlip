<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Collection\ProperList;
use Webgraphe\Phlip\Collection\Vector;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\KeywordSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenVectorSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\QuoteSymbol;

class Parser
{
    /**
     * @param LexemeStream $stream
     * @return ProperList
     * @throws Exception
     * @throws ParserException
     */
    public function parseLexemeStream(LexemeStream $stream): ProperList
    {
        /** @var ProperList[] $statements */
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

        return new ProperList(...$statements);
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
            return $this->extractProperList($stream);
        }

        if ($lexeme instanceof OpenVectorSymbol) {
            return $this->extractVector($stream);
        }

        if ($lexeme instanceof Atom) {
            return $lexeme;
        }

        if ($lexeme instanceof Comment) {
            return null;
        }

        throw new ParserException("Unexpected lexeme '$lexeme'");
    }

    private function extractProperList(LexemeStream $stream): ProperList
    {
        return new ProperList(
            ...$this->extractNextFormsUntilClosingSymbol(
                $stream,
                OpenListSymbol::instance()->getRelatedClosingSymbol()
            )
        );
    }

    private function extractVector(LexemeStream $stream): Vector
    {
        return new Vector(
            ...$this->extractNextFormsUntilClosingSymbol(
                $stream,
                OpenVectorSymbol::instance()->getRelatedClosingSymbol()
            )
        );
    }

    /**
     * @param LexemeStream $stream
     * @param Closing $symbol
     * @return FormContract[]
     */
    private function extractNextFormsUntilClosingSymbol(LexemeStream $stream, Closing $symbol): array
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

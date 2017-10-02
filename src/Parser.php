<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\FormCollection\Pair;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\FormCollection\Vector;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\DotSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenMapSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenVectorSymbol;
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
                if ($statement = $this->parseNextForm($stream)) {
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
    protected function parseNextForm(LexemeStream $stream): FormContract
    {
        $lexeme = $stream->current();
        $stream->next();

        switch (true) {
            case $lexeme instanceof QuoteSymbol:
                return new QuotedForm($this->parseNextForm($stream));

            case $lexeme instanceof OpenListSymbol:
                return $this->extractList($stream);

            case $lexeme instanceof OpenVectorSymbol:
                return $this->extractVector($stream);

            case $lexeme instanceof OpenMapSymbol:
                return $this->extractMap($stream);

            case $lexeme instanceof FormContract:
                return $lexeme;

            default:
                throw new ParserException("Unexpected lexeme '$lexeme'");
        }
    }

    protected function extractList(LexemeStream $stream): FormContract
    {
        $list = [];
        $closingSymbol = OpenListSymbol::instance()->getRelatedClosingSymbol();
        while ($stream->current() !== $closingSymbol) {
            if ($stream->current() instanceof DotSymbol) {
                if (!$list) {
                    throw new ParserException("Malformed dot-notation pair; missing left-hand side");
                }
                if ($rest = $this->extractNextFormsUntilClosingSymbol($stream->next(), $closingSymbol)) {
                    if (count($rest) > 1) {
                        throw new ParserException("Malformed dot-notation pair; right-hand side has too many forms");
                    }
                    if ($rest[0] instanceof ProperList) {
                        return new ProperList(...array_merge($list, $rest));
                    }

                    return Pair::fromForms(...array_merge($list, $rest));
                }

                throw new ParserException("Malformed dot-notation pair; missing right-hand side");
            }
            $list[] = $this->parseNextForm($stream);
        }
        $stream->next();

        return new ProperList(...$list);
    }

    protected function extractVector(LexemeStream $stream): Vector
    {
        $elements = $this->extractNextFormsUntilClosingSymbol(
            $stream,
            OpenVectorSymbol::instance()->getRelatedClosingSymbol()
        );

        return new Vector(...$elements);
    }

    protected function extractMap(LexemeStream $stream): Map
    {
        $pairs = $this->extractNextFormsUntilClosingSymbol(
            $stream,
            OpenMapSymbol::instance()->getRelatedClosingSymbol()
        );

        return new Map(...$pairs);
    }

    /**
     * @param LexemeStream $stream
     * @param Closing $symbol
     * @return FormContract[]
     */
    protected function extractNextFormsUntilClosingSymbol(LexemeStream $stream, Closing $symbol): array
    {
        $list = [];
        while ($stream->current() !== $symbol) {
            $list[] = $this->parseNextForm($stream);
        }
        $stream->next();

        return $list;
    }
}

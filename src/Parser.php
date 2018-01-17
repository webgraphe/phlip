<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ParserException;
use Webgraphe\Phlip\FormCollection\Map;
use Webgraphe\Phlip\FormCollection\Pair;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\FormCollection\Vector;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\DotSymbol;
use Webgraphe\Phlip\Symbol\Mark\TildeSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenListSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenMapSymbol;
use Webgraphe\Phlip\Symbol\Opening\OpenVectorSymbol;
use Webgraphe\Phlip\Symbol\Mark\GraveAccentSymbol;
use Webgraphe\Phlip\Symbol\Mark\StraightSingleMarkSymbol;

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

        if ($lexeme instanceof StraightSingleMarkSymbol) {
            return new MarkedForm\QuotedForm($this->parseNextForm($stream));
        }

        if ($lexeme instanceof GraveAccentSymbol) {
            return new MarkedForm\QuasiquotedForm($this->parseNextForm($stream));
        }

        if ($lexeme instanceof TildeSymbol) {
            return new MarkedForm\UnquotedForm($this->parseNextForm($stream));
        }

        if ($lexeme instanceof OpenListSymbol) {
            return $this->extractList($stream);
        }

        if ($lexeme instanceof OpenVectorSymbol) {
            return $this->extractVector($stream);
        }

        if ($lexeme instanceof OpenMapSymbol) {
            return $this->extractMap($stream);
        }

        if ($lexeme instanceof FormContract) {
            return $lexeme;
        }

        throw new ParserException("Unexpected lexeme '$lexeme'");
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
                        return new ProperList(...$list, ...$rest[0]->all());
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

    /**
     * @param LexemeStream $stream
     * @return Map
     * @throws AssertionException
     */
    protected function extractMap(LexemeStream $stream): Map
    {
        $keyValues = $this->extractNextFormsUntilClosingSymbol(
            $stream,
            OpenMapSymbol::instance()->getRelatedClosingSymbol()
        );

        if (($count = count($keyValues)) % 2) {
            throw new AssertionException("Malformed map; non-even number of key-value items");
        }

        $pairs = [];
        for ($i = 0; $i < $count; $i += 2) {
            $pairs[] = new ProperList($keyValues[$i], $keyValues[$i + 1]);
        }

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

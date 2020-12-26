<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class WhileOperation extends PrimaryOperation
{
    /** @var string */
    const IDENTIFIER = 'while';

    /** @var string */
    private $identifier;

    public function __construct(string $identifier = self::IDENTIFIER)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [$this->identifier];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $condition = $forms->assertHead();
        while ($context->execute($condition)) {
            $statements = $forms->getTail();
            while ($statement = $statements->getHead()) {
                $statements = $statements->getTail();
                $context->execute($statement);
            }
        }

        return null;
    }
}

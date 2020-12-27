<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class ExecuteOperation extends PrimaryOperation
{
    const IDENTIFIER = 'execute';

    /**
     * @param ContextContract $context
     * @return static
     * @throws ContextException
     */
    public static function contextBounded(ContextContract $context): self
    {
        return (new static())->withBoundedContext($context);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws ContextException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $result = null;

        $boundedContext = $this->assertBoundedContext();
        while ($head = $forms->getHead()) {
            $forms = $forms->getTail();
            $result = $boundedContext->execute($context->execute($head));
        }

        return $result;
    }
}

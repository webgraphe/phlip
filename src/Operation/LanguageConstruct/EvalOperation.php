<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\ContextAnchor;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

/**
 * Evaluates a given expression in the bound context of the operation.
 *
 * Because it's lexically scoped, the given expression cannot be evaluated in the local context.
 */
class EvalOperation extends PrimaryOperation
{
    const IDENTIFIER = 'eval';

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
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $result = null;

        $env = ($tailHead = $forms->getTailHead())
            ? $this->assertContextAnchor($context->execute($tailHead))->getContext()
            : $this->assertBoundedContext();
        $result = $env->execute($this->assertForm($context->execute($forms->assertHead())));

        return $result;
    }

    /**
     * @param mixed $thing
     * @return FormContract
     * @throws AssertionException
     */
    private function assertForm($thing): FormContract
    {
        if ($thing instanceof FormContract) {
            return $thing;
        }

        $type = is_object($thing) ? get_class($thing) : gettype($thing);

        throw new AssertionException("Can't evaluate; not a form: ($type) {$thing}");
    }

    /**
     * @param mixed $thing
     * @return ContextAnchor
     * @throws AssertionException
     */
    private function assertContextAnchor($thing): ContextAnchor
    {
        if ($thing instanceof ContextAnchor) {
            return $thing;
        }

        throw new AssertionException("Not a context anchor");
    }
}

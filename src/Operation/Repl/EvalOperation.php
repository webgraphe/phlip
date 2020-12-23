<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Throwable;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;
use Webgraphe\Phlip\Program;

class EvalOperation extends PrimaryOperation
{
    /** @var string */
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
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        try {
            $source = $context->execute($forms->assertHead());

            return strlen($source) ? Program::parse($source)->execute($context) : null;
        } catch (Throwable $t) {
            return $t;
        }
    }
}

<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;
use Webgraphe\Phlip\Program;

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
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        try {
            $source = $context->execute($forms->assertHead());

            return Program::parse($source)->execute($context);
        } catch (\Throwable $t) {
            return $t;
        }
    }
}

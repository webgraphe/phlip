<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class DefineOperation extends PrimaryOperation
{
    const IDENTIFIER = 'define';

    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $variable = $forms->assertHead();

        switch (true) {
            case $variable instanceof ProperList:
                $name = IdentifierAtom::assertStaticType($variable->assertHead());

                return $context->define(
                    $name->getValue(),
                    LambdaOperation::invokeStatically(
                        $context,
                        $variable->getTail(),
                        $forms->getTail()
                    )
                );

            case $variable instanceof IdentifierAtom:
                return $context->define(
                    $variable->getValue(),
                    $forms->getTail()->assertHead()->evaluate($context)
                );
        }

        throw EvaluationException::fromForm($variable, "Malformed define");
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

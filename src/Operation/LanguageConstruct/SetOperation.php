<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class SetOperation extends PrimaryOperation
{
    const IDENTIFIER = 'set';

    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $variable = $forms->getHead();

        switch (true) {
            case $variable instanceof ProperList:
                $name = IdentifierAtom::assertStaticType($variable->getHead());

                return $context->set(
                    $name->getValue(),
                    LambdaOperation::invokeStatically(
                        $context,
                        $variable->getTail(),
                        $forms->getTail()
                    )
                );

            case $variable instanceof IdentifierAtom:
                return $context->set(
                    $variable->getValue(),
                    $forms->getTail()->assertHead()->evaluate($context)
                );
        }

        throw EvaluationException::fromForm($variable, "Malformed set");
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

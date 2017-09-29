<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class SetOperation extends PrimaryOperation
{
    const IDENTIFIER = 'set';

    protected function invoke(ContextContract $context, ProperList $expressions)
    {
        $variable = $expressions->getHead();

        switch (true) {
            case $variable instanceof ProperList:
                $name = IdentifierAtom::assertStaticType($variable->getHead());

                return $context->set(
                    $name->getValue(),
                    LambdaOperation::invokeStatically(
                        $context,
                        $variable->getTail(),
                        $expressions->getTail()
                    )
                );

            case $variable instanceof IdentifierAtom:
                return $context->set(
                    $variable->getValue(),
                    $expressions->getTail()->assertHead()->evaluate($context)
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

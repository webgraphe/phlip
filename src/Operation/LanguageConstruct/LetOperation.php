<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class LetOperation extends PrimaryOperation
{
    const IDENTIFIER = 'let';

    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $context = $context->stack();

        $variables = ProperList::assertStaticType($forms->getHead());
        while ($variables && $variable = ProperList::assertStaticType($variables->getHead())) {
            $variables = $variables->getTail();
            $name = $variable->getHead();
            switch (true) {
                case $name instanceof ProperList:
                    $lambdaName = IdentifierAtom::assertStaticType($name->getHead());
                    $context->let(
                        $lambdaName->getValue(),
                        LambdaOperation::invokeStatically(
                            $context,
                            $name->getTail(),
                            $variable->getTail()
                        )
                    );
                    break;

                case $name instanceof IdentifierAtom:
                    $context->let(
                        $name->getValue(),
                        $variable->getTail()->assertHead()->evaluate($context)
                    );
                    break;

                default:
                    throw EvaluationException::fromForm($name, 'Malformed let');
            }
        }

        $result = null;
        $statements = $forms->getTail();
        while ($statement = $statements->getHead()) {
            $result = $statement->evaluate($context);
            $statements = $statements->getTail();
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

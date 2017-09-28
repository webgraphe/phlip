<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class LetOperation extends PrimaryOperation
{
    const IDENTIFIER = 'let';

    protected function invoke(ContextContract $context, FormList $expressions)
    {
        $context = $context->stack();

        $variables = FormList::assertStaticType($expressions->getHead());
        while ($variables && $variable = FormList::assertStaticType($variables->getHead())) {
            $variables = $variables->getTail();
            $name = $variable->getHead();
            switch (true) {
                case $name instanceof FormList:
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
        $statements = $expressions->getTail();
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

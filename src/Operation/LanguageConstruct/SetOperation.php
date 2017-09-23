<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryFunction;

class SetOperation extends PrimaryFunction
{
    const IDENTIFIER = 'set';

    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $variable = $expressions->getHeadExpression();

        switch (true) {
            case $variable instanceof ExpressionList:
                $name = IdentifierAtom::assertStaticType($variable->getHeadExpression());

                return $context->set(
                    $name->getValue(),
                    LambdaOperation::invokeStatically(
                        $context,
                        $variable->getTailExpressions(),
                        $expressions->getTailExpressions()
                    )
                );

            case $variable instanceof IdentifierAtom:
                return $context->set(
                    $variable->getValue(),
                    $expressions->getTailExpressions()->assertHeadExpression()->evaluate($context)
                );
        }

        throw EvaluationException::fromExpression($variable, "Malformed set");
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

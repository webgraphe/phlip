<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryFunction;

class LetOperation extends PrimaryFunction
{
    const IDENTIFIER = 'let';

    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $context = new Context($context);

        $variables = ExpressionList::assertStaticType($expressions->getHeadExpression());
        while ($variables && $variable = ExpressionList::assertStaticType($variables->getHeadExpression())) {
            $variables = $variables->getTailExpressions();
            $name = $variable->getHeadExpression();
            switch (true) {
                case $name instanceof ExpressionList:
                    $lambdaName = IdentifierAtom::assertStaticType($name->getHeadExpression());
                    $context->let(
                        $lambdaName->getValue(),
                        LambdaOperation::invokeStatically(
                            $context,
                            $name->getTailExpressions(),
                            $variable->getTailExpressions()
                        )
                    );
                    break;

                case $name instanceof IdentifierAtom:
                    $context->let(
                        $name->getValue(),
                        $variable->getTailExpressions()->assertHeadExpression()->evaluate($context)
                    );
                    break;

                default:
                    throw new \RuntimeException("Malformed let");
            }
        }

        $result = null;
        $statements = $expressions->getTailExpressions();
        while ($statement = $statements->getHeadExpression()) {
            $result = $statement->evaluate($context);
            $statements = $statements->getTailExpressions();
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

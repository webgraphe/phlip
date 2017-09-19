<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct;

class DefineOperation extends LanguageConstruct
{
    const IDENTIFIER = 'define';

    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $variable = $expressions->assertHeadExpression();

        switch (true) {
            case $variable instanceof ExpressionList:
                $name = IdentifierAtom::assertStaticType($variable->assertHeadExpression());

                return $context->define(
                    $name->getValue(),
                    LambdaOperation::invokeStatically(
                        $context,
                        $variable->getTailExpressions(),
                        $expressions->getTailExpressions()
                    )
                );

            case $variable instanceof IdentifierAtom:
                return $context->define(
                    $variable->getValue(),
                    $expressions->getTailExpressions()->assertHeadExpression()->evaluate($context)
                );
        }

        throw new \RuntimeException("Malformed define");
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

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
        $localContext = $context->stack();

        $head = $forms->assertHead();
        $tail = $forms->getTail();

        $letName = $head instanceof IdentifierAtom ? $head->getValue() : null;
        [$parameters, $arguments] = $this->buildParameterArguments(
            $localContext,
            ProperList::assertStaticType($letName ? $tail->assertHead() : $head)
        );
        $statements = $letName ? $tail->getTail() : $tail;

        $lambda = LambdaOperation::invokeStatic($localContext, $parameters, ...$statements);

        if ($letName) {
            $localContext->let($letName, $lambda);
        }

        return call_user_func($lambda, ...$arguments);
    }

    private function buildParameterArguments(ContextContract $context, ProperList $variables): array
    {
        $parameters = [];
        $arguments = [];
        while ($variables && $variable = ProperList::assertStaticType($variables->getHead())) {
            $variables = $variables->getTail();
            $name = $variable->getHead();
            switch (true) {
                case $name instanceof ProperList:
                    $lambdaName = IdentifierAtom::assertStaticType($name->getHead());
                    $namedLambda = LambdaOperation::invokeStatic(
                        $context,
                        $name->getTail(),
                        ...$variable->getTail()
                    );
                    $context->let($lambdaName->getValue(), $namedLambda);
                    $parameters[] = $lambdaName;
                    $arguments[] = $namedLambda;
                    break;

                case $name instanceof IdentifierAtom:
                    $parameters[] = $name;
                    $arguments[] = $variable->getTail()->assertHead()->evaluate($context);
                    break;

                default:
                    throw EvaluationException::fromForm($name, 'Malformed let');
            }
        }

        return [new ProperList(...$parameters), $arguments];
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

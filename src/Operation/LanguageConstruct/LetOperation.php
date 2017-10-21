<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
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
                        ...$variable->getTail()->all()
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

    public function walk(WalkerContract $walker, FormContract ...$forms): array
    {
        $statement = new ProperList(...$forms);
        $head = $statement->assertHead();
        $tail = $statement->getTail();

        if ($head instanceof IdentifierAtom) {
            return array_merge(
                [
                    $walker($head),
                    $this->walkParameterArguments($walker, ProperList::assertStaticType($tail->assertHead()))
                ],
                array_map($walker, $tail->getTail()->all())
            );
        }

        return array_merge(
            [$this->walkParameterArguments($walker, ProperList::assertStaticType($head))],
            array_map($walker, $tail->all())
        );
    }

    private function walkParameterArguments(WalkerContract $walker, ProperList $variables): ProperList
    {
        $pairs = [];

        while ($variables && $variable = ProperList::assertStaticType($variables->getHead())) {
            $variables = $variables->getTail();
            $name = $variable->getHead();
            switch (true) {
                case $name instanceof ProperList:
                    $pairs[] = new ProperList($name, ...array_map($walker, $variable->getTail()->all()));
                    break;

                default:
                    $pairs[] = new ProperList(...array_map($walker, $variable->all()));
            }
        }

        return new ProperList(...$pairs);
    }
}

<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class LetOperation extends PrimaryOperation
{
    /** @var string */
    const IDENTIFIER = 'let';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws AssertionException
     */
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

    /**
     * @param ContextContract $context
     * @param ProperList $variables
     * @return array
     * @throws AssertionException
     */
    private function buildParameterArguments(ContextContract $context, ProperList $variables): array
    {
        $parameters = [];
        $arguments = [];
        while ($variables && $variable = ProperList::assertStaticType($variables->getHead())) {
            $variables = $variables->getTail();
            $name = $variable->assertHead();
            if ($name instanceof ProperList) {
                $parameters[] = IdentifierAtom::assertStaticType($name->getHead());
                $arguments[] = LambdaOperation::invokeStatic(
                    $context,
                    $name->getTail(),
                    ...$variable->getTail()->all()
                );
            } elseif ($name instanceof IdentifierAtom) {
                $parameters[] = $name;
                $arguments[] = $context->execute($variable->getTail()->assertHead());
            } else {
                throw new AssertionException('Malformed let');
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

    /**
     * @param WalkerContract $walker
     * @param FormContract[] ...$forms
     * @return FormContract[]
     * @throws AssertionException
     */
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

    /**
     * @param WalkerContract $walker
     * @param ProperList $variables
     * @return ProperList
     * @throws AssertionException
     */
    private function walkParameterArguments(WalkerContract $walker, ProperList $variables): ProperList
    {
        $pairs = [];

        while ($variables && $variable = ProperList::assertStaticType($variables->getHead())) {
            $variables = $variables->getTail();
            $name = $variable->getHead();
            $pairs[] = $name instanceof ProperList
                ? new ProperList($name, ...array_map($walker, $variable->getTail()->all()))
                : new ProperList(...array_map($walker, $variable->all()));
        }

        return new ProperList(...$pairs);
    }
}

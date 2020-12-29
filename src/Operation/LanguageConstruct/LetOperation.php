<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\ManualOperation;

class LetOperation extends ManualOperation
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
        $context = $context->stack();
        $head = $forms->assertHead();
        $tail = $forms->getTail();

        $letName = $head instanceof IdentifierAtom ? $head->getValue() : null;
        [$parameters, $arguments] = $this->buildParameterArguments(
            $context,
            ProperList::assertStaticType($letName ? $tail->assertHead() : $head)
        );
        $statements = $letName ? $tail->getTail() : $tail;

        $lambda = LambdaOperation::invokeStatic($context, $parameters, ...$statements);

        if ($letName) {
            $context->let($letName, $lambda);
        }

        return call_user_func($lambda, ...$arguments);
    }

    /**
     * @param ContextContract $context
     * @param ProperList $variables
     * @return array
     * @throws AssertionException
     */
    protected function buildParameterArguments(ContextContract $context, ProperList $variables): array
    {
        $parameters = [];
        $arguments = [];
        while (($head = $variables->getHead()) && $variable = ProperList::assertStaticType($head)) {
            $variables = $variables->getTail();
            $name = $variable->assertHead();
            if ($name instanceof ProperList) {
                $parameter = IdentifierAtom::assertStaticType($name->getHead());
                $argument = LambdaOperation::invokeStatic(
                    $context,
                    $name->getTail(),
                    ...$variable->getTail()
                );
            } elseif ($name instanceof IdentifierAtom) {
                $parameter = $name;
                $argument = $context->execute($variable->assertTailHead());
            } else {
                throw new AssertionException('Malformed let');
            }

            if (is_callable($argument)) {
                $context->let($parameter->getValue(), $argument);
            }

            $parameters[] = $parameter;
            $arguments[] = $argument;
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

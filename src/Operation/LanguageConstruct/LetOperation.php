<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class LetOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'let';

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $scope = $scope->stack();
        $head = $forms->assertHead();
        $tail = $forms->getTail();

        $letName = $head instanceof IdentifierAtom ? $head->getValue() : null;
        [$parameters, $arguments] = $this->buildParameterArguments(
            $scope,
            FormList::assertStaticType($letName ? $tail->assertHead() : $head)
        );
        $statements = $letName ? $tail->getTail() : $tail;

        $lambda = LambdaOperation::invokeStatic($scope, $parameters, ...$statements);

        if ($letName) {
            $scope->let($letName, $lambda);
        }

        return call_user_func($lambda, ...$arguments);
    }

    /**
     * @param ScopeContract $scope
     * @param FormList $variables
     * @return array
     * @throws AssertionException
     */
    protected function buildParameterArguments(ScopeContract $scope, FormList $variables): array
    {
        $parameters = [];
        $arguments = [];
        while (($head = $variables->getHead()) && $variable = FormList::assertStaticType($head)) {
            $variables = $variables->getTail();
            $name = $variable->assertHead();
            if ($name instanceof FormList) {
                $parameter = IdentifierAtom::assertStaticType($name->getHead());
                $argument = LambdaOperation::invokeStatic(
                    $scope,
                    $name->getTail(),
                    ...$variable->getTail()
                );
            } elseif ($name instanceof IdentifierAtom) {
                $parameter = $name;
                $argument = $scope->execute($variable->assertTailHead());
            } else {
                throw new AssertionException('Malformed let');
            }

            if (is_callable($argument)) {
                $scope->let($parameter->getValue(), $argument);
            }

            $parameters[] = $parameter;
            $arguments[] = $argument;
        }

        return [new FormList(...$parameters), $arguments];
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

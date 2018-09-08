<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class LambdaOperation extends PrimaryOperation
{
    const IDENTIFIER = 'lambda';

    /**
     * @param ContextContract $context
     * @param ProperList $parameters
     * @param FormContract ...$statements
     * @return \Closure
     * @throws AssertionException
     */
    public static function invokeStatic(
        ContextContract $context,
        ProperList $parameters,
        FormContract ...$statements
    ) {
        return (new self)->invoke($context, new ProperList($parameters, ...$statements));
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return \Closure
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $parameters = ProperList::assertStaticType($forms->assertHead());
        $statements = $forms->getTail();

        return function () use ($context, $parameters, $statements) {
            $localContext = $context->stack();

            $arguments = self::assertArgumentsMatchingParameters($parameters, func_get_args());

            while ($arguments) {
                $argument = array_shift($arguments);
                $parameter = IdentifierAtom::assertStaticType($parameters->assertHead());
                $parameters = $parameters->getTail();
                $localContext->let($parameter->getValue(), $argument);
            }

            $result = null;
            while ($statement = $statements->getHead()) {
                $result = $localContext->execute($statement);
                $statements = $statements->getTail();
            }

            return $result;
        };
    }

    /**
     * @param ProperList $parameters
     * @param array $arguments
     * @return array
     * @throws AssertionException
     */
    private static function assertArgumentsMatchingParameters(ProperList $parameters, array $arguments): array
    {
        $argumentCount = count($arguments);
        $parameterCount = count($parameters);
        if ($parameterCount !== $argumentCount) {
            throw new AssertionException("Arguments mismatch parameter definition");
        }

        return $arguments;
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
     * @param FormContract ...$forms
     * @return array
     * @throws AssertionException
     */
    public function walk(WalkerContract $walker, FormContract ...$forms): array
    {
        $statement = new ProperList(...$forms);

        return array_merge([$statement->assertHead()], array_map($walker, $statement->getTail()->all()));
    }
}

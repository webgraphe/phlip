<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class LambdaOperation extends PrimaryOperation
{
    const IDENTIFIER = 'lambda';

    public static function invokeStatic(
        ContextContract $context,
        ProperList $parameters,
        FormContract ...$statements
    )
    {
        return (new self)->invoke($context, new ProperList($parameters, ...$statements));
    }

    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $parameters = ProperList::assertStaticType($forms->getHead());
        $statements = $forms->getTail();

        return function () use ($context, $parameters, $statements) {
            $localContext = $context->stack();

            $arguments = self::assertArgumentsMatchingParameters($parameters, func_get_args());

            while ($arguments) {
                $argument = array_shift($arguments);
                $parameter = IdentifierAtom::assertStaticType($parameters->getHead());
                $parameters = $parameters->getTail();
                $localContext->let($parameter->getValue(), $argument);
            }

            $result = null;
            while ($statement = $statements->getHead()) {
                $result = $statement->evaluate($localContext);
                $statements = $statements->getTail();
            }

            return $result;
        };
    }

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
}

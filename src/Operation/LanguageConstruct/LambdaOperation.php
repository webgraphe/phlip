<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Closure;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class LambdaOperation extends ManualOperation
{
    /** @var string */
    const IDENTIFIER = 'lambda';

    /**
     * @param ContextContract $context
     * @param FormList $parameters
     * @param FormContract ...$statements
     * @return Closure
     * @throws AssertionException
     */
    public static function invokeStatic(
        ContextContract $context,
        FormList $parameters,
        FormContract ...$statements
    ): Closure {
        return (new static())->invoke($context, new FormList($parameters, ...$statements));
    }

    /**
     * @param ContextContract $context
     * @param FormList $forms
     * @return Closure
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, FormList $forms): Closure
    {
        $parameters = FormList::assertStaticType($forms->assertHead());
        $statements = $forms->getTail();

        return function () use ($context, $parameters, $statements) {
            $context = $context->stack();

            $arguments = static::assertArgumentsMatchingParameters($parameters, func_get_args());

            while ($arguments) {
                $argument = array_shift($arguments);
                $parameter = IdentifierAtom::assertStaticType($parameters->assertHead());
                $parameters = $parameters->getTail();
                $context->let($parameter->getValue(), $argument);
            }

            $result = null;
            while ($statement = $statements->getHead()) {
                $result = $context->execute($statement);
                $statements = $statements->getTail();
            }

            return $result;
        };
    }

    /**
     * @param FormList $parameters
     * @param array $arguments
     * @return array
     * @throws AssertionException
     */
    protected static function assertArgumentsMatchingParameters(FormList $parameters, array $arguments): array
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

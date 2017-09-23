<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryFunction;

class LambdaOperation extends PrimaryFunction
{
    const IDENTIFIER = 'lambda';

    public static function invokeStatically(
        ContextContract $context,
        ExpressionList $parameters,
        ExpressionList $statements
    )
    {
        return (new self)->invoke($context, new ExpressionList($parameters, ...$statements->all()));
    }

    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $parameters = ExpressionList::assertStaticType($expressions->getHeadExpression());
        $statements = $expressions->getTailExpressions();

        return function () use ($context, $parameters, $statements) {
            $context = new Context($context);

            $arguments = func_get_args();
            $argumentCount = count($arguments);
            $parameterCount = count($parameters);
            if ($parameterCount !== $argumentCount) {
                throw new EvaluationException("Arguments mismatch parameter definition");
            }

            while ($arguments) {
                $argument = array_shift($arguments);
                $parameter = IdentifierAtom::assertStaticType($parameters->getHeadExpression());
                $parameters = $parameters->getTailExpressions();
                $context->let($parameter->getValue(), $argument);
            }

            $result = null;
            while ($statement = $statements->getHeadExpression()) {
                $result = $statement->evaluate($context);
                $statements = $statements->getTailExpressions();
            }

            return $result;
        };
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

<?php

namespace Webgraphe\Phlip\Context;

use Webgraphe\Phlip\Atom\BooleanAtom;
use Webgraphe\Phlip\Atom\NullAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Operation;

class PhlipyContext extends Context
{
    public function __construct()
    {
        self::withFinalAtoms($this);
        self::withLispPrimitives($this);
        self::withExtraLanguageConstructs($this);
        self::withArithmeticOperators($this);
        self::withComparisonOperators($this);
        self::withLogicOperators($this);
    }

    private static function withFinalAtoms(ContextContract $context): ContextContract
    {
        $context->define((string)NullAtom::instance(), NullAtom::instance()->getValue());
        $context->define((string)BooleanAtom::true(), BooleanAtom::true()->getValue());
        $context->define((string)BooleanAtom::false(), BooleanAtom::false()->getValue());

        return $context;
    }

    public static function withLispPrimitives(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\LanguageConstruct\DefineOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\QuoteOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\CarOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\CdrOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\ConsOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\AtomOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\EqualityOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\CondOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\LambdaOperation);

        return $context;
    }

    public static function withExtraLanguageConstructs(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\LanguageConstruct\DefinedOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\LetOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\SetOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\IfOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\ListOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\Structures\DictionaryOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\WhileOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\BeginOperation);

        return $context;
    }

    public static function withArithmeticOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Arithmetic\AdditionOperation);
        self::defineOperation($context, new Operation\Arithmetic\SubtractionOperation);
        self::defineOperation($context, new Operation\Arithmetic\MultiplicationOperation);
        self::defineOperation($context, new Operation\Arithmetic\DivisionOperation);
        self::defineOperation($context, new Operation\Arithmetic\ModuloOperation);
        self::defineOperation($context, new Operation\Arithmetic\RemainderOperation);
        self::defineOperation($context, new Operation\Arithmetic\ExponentiationOperation);

        return $context;
    }

    public static function withComparisonOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Comparison\EqualityOperation);
        self::defineOperation($context, new Operation\Comparison\NotEqualOperation);
        self::defineOperation($context, new Operation\Comparison\GreaterThanOperation);
        self::defineOperation($context, new Operation\Comparison\GreaterThanOrEqualToOperation);
        self::defineOperation($context, new Operation\Comparison\LesserThanOperation);
        self::defineOperation($context, new Operation\Comparison\LesserThanOrEqualToOperation);
        self::defineOperation($context, new Operation\Comparison\SpaceshipOperation);

        return $context;
    }

    public static function withLogicOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Logic\AndOperation);
        self::defineOperation($context, new Operation\Logic\OrOperation);
        self::defineOperation($context, new Operation\Logic\NotOperation);
        self::defineOperation($context, new Operation\Logic\XorOperation);

        return $context;
    }

    public static function defineOperation(ContextContract $context, Operation $operation)
    {
        array_map(
            function (string $identifier) use ($context, $operation) {
                $context->define($identifier, $operation);
            },
            $operation->getIdentifiers()
        );
    }
}

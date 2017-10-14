<?php

namespace Webgraphe\Phlip\Context;

use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Operation;

class PhlipyContext extends Context
{
    /** @var string[] */
    const PHP_MATH_FUNCTIONS = [
        'abs',
        'acos',
        'acosh',
        'asin',
        'asinh',
        'atan2',
        'atan',
        'atanh',
        'base_convert' => 'base-convert',
        'bindec',
        'ceil',
        'cos',
        'cosh',
        'decbin',
        'dechex',
        'decoct',
        'deg2rad',
        'exp',
        'expm1',
        'floor',
        'fmod',
        'getrandmax' => 'rand-max',
        'hexdec',
        'hypot',
        'intdiv',
        'is_finite' => 'finite?',
        'is_infinite' => 'infinite?',
        'is_nan' => 'nan?',
        'lcg_value' => 'lcg-value',
        'log10',
        'log1p',
        'log',
        'max',
        'min',
        'mt_getrandmax' => 'mt-rand-max',
        'mt_rand' => 'mt-rand',
        'mt_srand' => 'mt-rand-seed',
        'octdec',
        'pi',
        'pow',
        'rad2deg',
        'rand',
        'round',
        'sin',
        'sinh',
        'sqrt',
        'srand' => 'rand-seed',
        'tan',
        'tanh',
    ];

    public function __construct()
    {
        self::withLispPrimitives($this);
        self::withExtraLanguageConstructs($this);
        self::withTypeOperators($this);
        self::withArithmeticOperators($this);
        self::withComparisonOperators($this);
        self::withLogicOperators($this);
        self::withBitwiseOperators($this);
        self::withPhpMathFunctions($this);
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
        self::defineOperation($context, new Operation\LanguageConstruct\WhileOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\BeginOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\EvalOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\MacroOperation);

        return $context;
    }

    public static function withTypeOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Type\IsArrayOperation);
        self::defineOperation($context, new Operation\Type\IsCallableOperation);
        self::defineOperation($context, new Operation\Type\IsFormOperation);
        self::defineOperation($context, new Operation\Type\IsIdentifierOperation);
        self::defineOperation($context, new Operation\Type\IsKeywordOperation);
        self::defineOperation($context, new Operation\Type\IsLambdaOperation);
        self::defineOperation($context, new Operation\Type\IsListOperation);
        self::defineOperation($context, new Operation\Type\IsOperationOperation);
        self::defineOperation($context, new Operation\Type\IsPairOperation);
        self::defineOperation($context, new Operation\Type\IsNumberOperation);
        self::defineOperation($context, new Operation\Type\IsStringOperation);

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

    public static function withBitwiseOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Bitwise\AndOperation);
        self::defineOperation($context, new Operation\Bitwise\OrOperation);
        self::defineOperation($context, new Operation\Bitwise\NotOperation);
        self::defineOperation($context, new Operation\Bitwise\XorOperation);
        self::defineOperation($context, new Operation\Bitwise\ShiftLeftOperation);
        self::defineOperation($context, new Operation\Bitwise\ShiftRightOperation);

        return $context;
    }

    public static function withPhpMathFunctions(ContextContract $context): ContextContract
    {
        array_map(
            function ($key, $value) use ($context) {
                self::wrapPhpFunction($context, is_numeric($key) ? $value : $key, $value);
            },
            array_keys(self::PHP_MATH_FUNCTIONS),
            array_values(self::PHP_MATH_FUNCTIONS)
        );

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

    public static function wrapPhpFunction(ContextContract $context, string $function, string $alias = null)
    {
        $context->define($alias ?? $function, function () use ($function) {
            return call_user_func_array($function, func_get_args());
        });
    }
}

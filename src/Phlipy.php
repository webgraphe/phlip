<?php

namespace Webgraphe\Phlip;

use RuntimeException;
use Webgraphe\Phlip\Contracts\ContextContract;

class Phlipy
{
    public static function basic(ContextContract $context = null): ContextContract
    {
        $context = $context ?? new Context;

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

    public static function standard(ContextContract $context = null): ContextContract
    {
        $context = self::basic($context);

        self::withExtraLanguageConstructs($context);
        self::withTypeOperators($context);
        self::withArithmeticOperators($context);
        self::withComparisonOperators($context);
        self::withLogicOperators($context);
        self::withBitwiseOperators($context);
        self::withPhpInterop($context);
        self::withStringFunctions($context);
        self::withPhpMathFunctions($context);
        self::withErrors($context);

        return $context;
    }

    protected static function withExtraLanguageConstructs(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\LanguageConstruct\DefinedOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\LetOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\SetOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\IfOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\ListOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\WhileOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\BeginOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\ExecuteOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\MacroOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\LengthOperation);
        self::defineOperation($context, new Operation\LanguageConstruct\MacroExpandOperation);

        return $context;
    }

    protected static function withTypeOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Type\IsVectorOperation);
        self::defineOperation($context, new Operation\Type\IsMapOperation);
        self::defineOperation($context, new Operation\Type\IsCallableOperation);
        self::defineOperation($context, new Operation\Type\IsFormOperation);
        self::defineOperation($context, new Operation\Type\IsIdentifierOperation);
        self::defineOperation($context, new Operation\Type\IsKeywordOperation);
        self::defineOperation($context, new Operation\Type\IsLambdaOperation);
        self::defineOperation($context, new Operation\Type\IsListOperation);
        self::defineOperation($context, new Operation\Type\IsMacroOperation);
        self::defineOperation($context, new Operation\Type\IsOperationOperation);
        self::defineOperation($context, new Operation\Type\IsPairOperation);
        self::defineOperation($context, new Operation\Type\IsNumberOperation);
        self::defineOperation($context, new Operation\Type\IsStringOperation);

        return $context;
    }

    protected static function withArithmeticOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Arithmetic\AdditionOperation);
        self::defineOperation($context, new Operation\Arithmetic\SubtractionOperation);
        self::defineOperation($context, new Operation\Arithmetic\MultiplicationOperation);
        self::defineOperation($context, new Operation\Arithmetic\DivisionOperation);
        self::defineOperation($context, new Operation\Arithmetic\ModuloOperation);
        self::defineOperation($context, new Operation\Arithmetic\ExponentiationOperation);

        return $context;
    }

    protected static function withComparisonOperators(ContextContract $context): ContextContract
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

    protected static function withLogicOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Logic\AndOperation);
        self::defineOperation($context, new Operation\Logic\OrOperation);
        self::defineOperation($context, new Operation\Logic\NotOperation);
        self::defineOperation($context, new Operation\Logic\XorOperation);

        return $context;
    }

    protected static function withBitwiseOperators(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Bitwise\AndOperation);
        self::defineOperation($context, new Operation\Bitwise\OrOperation);
        self::defineOperation($context, new Operation\Bitwise\NotOperation);
        self::defineOperation($context, new Operation\Bitwise\XorOperation);
        self::defineOperation($context, new Operation\Bitwise\ShiftLeftOperation);
        self::defineOperation($context, new Operation\Bitwise\ShiftRightOperation);

        return $context;
    }

    protected static function withPhpInterop(ContextContract $context): ContextContract
    {
        self::defineOperation($context, new Operation\Interop\StaticOperation());
        self::defineOperation($context, new Operation\Interop\NewOperation());
        self::defineOperation($context, new Operation\Interop\ObjectOperation());
        self::defineOperation($context, new Operation\Interop\CloneOperation());
        self::defineOperation($context, new Operation\Interop\InstanceOperation());

        return $context;
    }

    protected static function withErrors(ContextContract $context): ContextContract
    {
        $context->define(
            'notice',
            function ($message) {
                trigger_error($message, E_USER_NOTICE);
            }
        );
        $context->define(
            'warning',
            function ($message) {
                trigger_error($message, E_USER_WARNING);
            }
        );
        $context->define(
            'error',
            function ($message) {
                trigger_error($message, E_USER_ERROR);
            }
        );
        $context->define(
            'deprecated',
            function ($message) {
                trigger_error($message, E_USER_DEPRECATED);
            }
        );

        return $context;
    }

    public static function withRepl(ContextContract $context, array $options = []): ContextContract
    {
        self::defineOperation(
            $context,
            !empty($options['read.multi-line'])
                ? Operation\Repl\ReadOperation::multiLine()
                : new Operation\Repl\ReadOperation()
        );

        self::defineOperation(
            $context,
            new Operation\LanguageConstruct\WhileOperation(
                isset($options['loop.identifier']) ? (string)$options['loop.identifier'] : 'loop'
            )
        );
        self::defineOperation($context, new Operation\Repl\EvalOperation);
        self::defineOperation(
            $context,
            $printOperation = new Operation\Repl\PrintOperation(
                isset($options['print.form-builder']) && $options['print.form-builder'] instanceof FormBuilder
                    ? $options['print.form-builder']
                    : null,
                isset($options['print.lexer']) && $options['print.lexer'] instanceof Lexer
                    ? $options['print.lexer']
                    : null,
                $options
            )
        );
        self::defineOperation($context, new Operation\Repl\ExitOperation);

        return $context;
    }

    protected static function withPhpMathFunctions(ContextContract $context): ContextContract
    {
        self::wrapPhpFunction($context, 'abs');
        self::wrapPhpFunction($context, 'acos');
        self::wrapPhpFunction($context, 'acosh');
        self::wrapPhpFunction($context, 'asin');
        self::wrapPhpFunction($context, 'asinh');
        self::wrapPhpFunction($context, 'atan2');
        self::wrapPhpFunction($context, 'atan');
        self::wrapPhpFunction($context, 'atanh');
        self::wrapPhpFunction($context, 'base_convert', 'base-convert');
        self::wrapPhpFunction($context, 'bindec');
        self::wrapPhpFunction($context, 'ceil');
        self::wrapPhpFunction($context, 'cos');
        self::wrapPhpFunction($context, 'cosh');
        self::wrapPhpFunction($context, 'decbin');
        self::wrapPhpFunction($context, 'dechex');
        self::wrapPhpFunction($context, 'decoct');
        self::wrapPhpFunction($context, 'deg2rad');
        self::wrapPhpFunction($context, 'exp');
        self::wrapPhpFunction($context, 'expm1');
        self::wrapPhpFunction($context, 'floor');
        self::wrapPhpFunction($context, 'fmod');
        self::wrapPhpFunction($context, 'getrandmax', 'rand-max');
        self::wrapPhpFunction($context, 'hexdec');
        self::wrapPhpFunction($context, 'hypot');
        self::wrapPhpFunction($context, 'intdiv');
        self::wrapPhpFunction($context, 'is_finite', 'finite?');
        self::wrapPhpFunction($context, 'is_infinite', 'infinite?');
        self::wrapPhpFunction($context, 'is_nan', 'nan?');
        self::wrapPhpFunction($context, 'lcg_value', 'lcg-value');
        self::wrapPhpFunction($context, 'log10');
        self::wrapPhpFunction($context, 'log1p');
        self::wrapPhpFunction($context, 'log');
        self::wrapPhpFunction($context, 'max');
        self::wrapPhpFunction($context, 'min');
        self::wrapPhpFunction($context, 'octdec');
        self::wrapPhpFunction($context, 'pi');
        self::wrapPhpFunction($context, 'pow');
        self::wrapPhpFunction($context, 'rad2deg');
        self::wrapPhpFunction($context, 'rand');
        self::wrapPhpFunction($context, 'round');
        self::wrapPhpFunction($context, 'sin');
        self::wrapPhpFunction($context, 'sinh');
        self::wrapPhpFunction($context, 'sqrt');
        self::wrapPhpFunction($context, 'srand', 'rand-seed');
        self::wrapPhpFunction($context, 'tan');
        self::wrapPhpFunction($context, 'tanh');

        return $context;
    }

    protected static function defineOperation(ContextContract $context, Operation $operation)
    {
        array_map(
            function (string $identifier) use ($context, $operation) {
                $context->define($identifier, $operation);
            },
            $operation->getIdentifiers()
        );
    }

    /**
     * @param ContextContract $context
     * @param string $function
     * @param string|null $alias
     * @throws RuntimeException
     */
    public static function wrapPhpFunction(ContextContract $context, string $function, string $alias = null)
    {
        if (!function_exists($function)) {
            throw new RuntimeException("Undefined function {$function}()");
        }

        $context->define($alias ?? $function, function () use ($function) {
            return call_user_func_array($function, func_get_args());
        });
    }

    private static function withStringFunctions(ContextContract $context): ContextContract
    {
        self::wrapPhpFunction($context, 'crc32');
        self::wrapPhpFunction($context, 'explode', 'split');
        self::wrapPhpFunction($context, 'implode');
        self::wrapPhpFunction($context, 'md5');
        self::wrapPhpFunction($context, 'number_format', 'number-format');
        self::wrapPhpFunction($context, 'sha1');
        self::wrapPhpFunction($context, 'sprintf', 'string-format');
        self::wrapPhpFunction($context, 'str_pad', 'string-pad');
        self::wrapPhpFunction($context, 'str_repeat', 'string-repeat');
        self::wrapPhpFunction($context, 'str_replace', 'string-replace');
        self::wrapPhpFunction($context, 'mb_strtolower', 'lowercase');
        self::wrapPhpFunction($context, 'mb_strtoupper', 'uppercase');
        self::wrapPhpFunction($context, 'mb_substr', 'substring');
        self::wrapPhpFunction($context, 'ltrim');
        self::wrapPhpFunction($context, 'rtrim');
        self::wrapPhpFunction($context, 'chop');
        self::wrapPhpFunction($context, 'trim');
        self::wrapPhpFunction($context, 'wordwrap');

        return $context;
    }
}

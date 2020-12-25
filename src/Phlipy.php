<?php

namespace Webgraphe\Phlip;

use RuntimeException;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\PhpClassInteroperableContract;

/**
 * Phlipy is context builder that quickly scaffolds a usable dialect depending on your needs.
 *
 * @see Phlipy::__construct() Convenient to evaluate data only
 * @see Phlipy::basic() For the hardcore developers, the bare minimum to achieve homoiconicity
 * @see Phlipy::passive() For anything that doesn't require interoperability with PHP classes
 * @see Phlipy::active() Should you need interoperability with PHP classes
 */
class Phlipy
{
    /** @var string */
    const LOOP_IDENTIFIER = 'loop';

    /** @var ContextContract */
    private $context;

    public function __construct(ContextContract $context)
    {
        $this->context = $context;
    }

    /**
     * @param ContextContract|null $context
     * @return static
     */
    public static function basic(ContextContract $context = null): self
    {
        return (new static($context ?? new Context()))
            ->withOperation(new Operation\LanguageConstruct\DefineOperation())
            ->withOperation(new Operation\LanguageConstruct\QuoteOperation())
            ->withOperation(new Operation\LanguageConstruct\CarOperation())
            ->withOperation(new Operation\LanguageConstruct\CdrOperation())
            ->withOperation(new Operation\LanguageConstruct\ConsOperation())
            ->withOperation(new Operation\LanguageConstruct\AtomOperation())
            ->withOperation(new Operation\LanguageConstruct\EqualityOperation())
            ->withOperation(new Operation\LanguageConstruct\CondOperation())
            ->withOperation(new Operation\LanguageConstruct\LambdaOperation());
    }

    /**
     * @param ContextContract|null $context
     * @return static
     */
    public static function passive(ContextContract $context = null): self
    {
        return static::basic($context ?? new Context())
            ->withExtraLanguageConstructs()
            ->withTypeOperators()
            ->withArithmeticOperators()
            ->withComparisonOperators()
            ->withLogicOperators()
            ->withBitwiseOperators()
            ->withPassivePhpInterop()
            ->withErrors();
    }

    /**
     * @param ContextContract|PhpClassInteroperableContext|null $context
     * @return static
     * @throws RuntimeException If the given content is not interoperable with PHP Classes
     */
    public static function active(ContextContract $context = null): self
    {
        return static::passive($context ?? new PhpClassInteroperableContext())
            ->withActivePhpInterop();
    }

    /**
     * @return static
     */
    protected function withExtraLanguageConstructs(): self
    {
        return $this
            ->withOperation(new Operation\LanguageConstruct\DefinedOperation())
            ->withOperation(new Operation\LanguageConstruct\LetOperation())
            ->withOperation(new Operation\LanguageConstruct\SetOperation())
            ->withOperation(new Operation\LanguageConstruct\IfOperation())
            ->withOperation(new Operation\LanguageConstruct\ListOperation())
            ->withOperation(new Operation\LanguageConstruct\WhileOperation())
            ->withOperation(new Operation\LanguageConstruct\BeginOperation())
            ->withOperation(new Operation\LanguageConstruct\ExecuteOperation())
            ->withOperation(new Operation\LanguageConstruct\MacroOperation())
            ->withOperation(new Operation\LanguageConstruct\LengthOperation())
            ->withOperation(new Operation\LanguageConstruct\MacroExpandOperation());
    }

    /**
     * @return static
     */
    protected function withTypeOperators(): self
    {
        return $this
            ->withOperation(new Operation\Type\IsVectorOperation())
            ->withOperation(new Operation\Type\IsMapOperation())
            ->withOperation(new Operation\Type\IsCallableOperation())
            ->withOperation(new Operation\Type\IsFormOperation())
            ->withOperation(new Operation\Type\IsIdentifierOperation())
            ->withOperation(new Operation\Type\IsKeywordOperation())
            ->withOperation(new Operation\Type\IsLambdaOperation())
            ->withOperation(new Operation\Type\IsListOperation())
            ->withOperation(new Operation\Type\IsMacroOperation())
            ->withOperation(new Operation\Type\IsOperationOperation())
            ->withOperation(new Operation\Type\IsPairOperation())
            ->withOperation(new Operation\Type\IsNumberOperation())
            ->withOperation(new Operation\Type\IsStringOperation());
    }

    /**
     * @return static
     */
    protected function withArithmeticOperators(): self
    {
        return $this
            ->withOperation(new Operation\Arithmetic\AdditionOperation())
            ->withOperation(new Operation\Arithmetic\SubtractionOperation())
            ->withOperation(new Operation\Arithmetic\MultiplicationOperation())
            ->withOperation(new Operation\Arithmetic\DivisionOperation())
            ->withOperation(new Operation\Arithmetic\ModuloOperation())
            ->withOperation(new Operation\Arithmetic\ExponentiationOperation());
    }

    /**
     * @return static
     */
    protected function withComparisonOperators(): self
    {
        return $this
            ->withOperation(new Operation\Comparison\EqualityOperation())
            ->withOperation(new Operation\Comparison\NotEqualOperation())
            ->withOperation(new Operation\Comparison\GreaterThanOperation())
            ->withOperation(new Operation\Comparison\GreaterThanOrEqualToOperation())
            ->withOperation(new Operation\Comparison\LesserThanOperation())
            ->withOperation(new Operation\Comparison\LesserThanOrEqualToOperation())
            ->withOperation(new Operation\Comparison\SpaceshipOperation());
    }

    /**
     * @return static
     */
    protected function withLogicOperators(): self
    {
        return $this
            ->withOperation(new Operation\Logic\AndOperation())
            ->withOperation(new Operation\Logic\OrOperation())
            ->withOperation(new Operation\Logic\NotOperation())
            ->withOperation(new Operation\Logic\XorOperation());
    }

    /**
     * @return static
     */
    protected function withBitwiseOperators(): self
    {
        return $this
            ->withOperation(new Operation\Bitwise\AndOperation())
            ->withOperation(new Operation\Bitwise\OrOperation())
            ->withOperation(new Operation\Bitwise\NotOperation())
            ->withOperation(new Operation\Bitwise\XorOperation())
            ->withOperation(new Operation\Bitwise\ShiftLeftOperation())
            ->withOperation(new Operation\Bitwise\ShiftRightOperation());
    }

    /**
     * @return static
     */
    protected function withPassivePhpInterop(): self
    {
        return $this->withOperation(new Operation\Interop\InstanceOperation());
    }

    /**
     * @return static
     * @throws RuntimeException If the inner context is not interoperable with PHP Classes
     */
    protected function withActivePhpInterop(): self
    {
        if (!($this->context instanceof PhpClassInteroperableContract)) {
            throw new RuntimeException("Context must be PHP Interoperable to support PHP interop operations");
        }

        return $this
            ->withOperation(new Operation\Interop\CloneOperation())
            ->withOperation(new Operation\Interop\NewOperation())
            ->withOperation(new Operation\Interop\StaticOperation())
            ->withOperation(new Operation\Interop\ObjectOperation());
    }

    /**
     * @return static
     */
    protected function withErrors(): self
    {
        $this->context->define(
            'notice',
            function ($message) {
                trigger_error($message, E_USER_NOTICE);
            }
        );
        $this->context->define(
            'warning',
            function ($message) {
                trigger_error($message, E_USER_WARNING);
            }
        );
        $this->context->define(
            'error',
            function ($message) {
                trigger_error($message, E_USER_ERROR);
            }
        );
        $this->context->define(
            'deprecated',
            function ($message) {
                trigger_error($message, E_USER_DEPRECATED);
            }
        );

        return $this;
    }

    /**
     * @param array $options
     * @return static
     */
    public function withRepl(array $options = []): self
    {
        return $this
            ->withOperation(
                !empty($options['read.multi-line'])
                    ? Operation\Repl\ReadOperation::multiLine($options['read.prompt'] ?? null)
                    : new Operation\Repl\ReadOperation($options['read.prompt'] ?? null)
            )
            ->withOperation(
                new Operation\LanguageConstruct\WhileOperation(
                    isset($options['loop.identifier']) ? (string)$options['loop.identifier'] : self::LOOP_IDENTIFIER
                )
            )
            ->withOperation(new Operation\Repl\EvalOperation())
            ->withOperation(
                $printOperation = new Operation\Repl\PrintOperation(
                    isset($options['print.form-builder']) && $options['print.form-builder'] instanceof FormBuilder
                        ? $options['print.form-builder']
                        : null,
                    isset($options['print.lexer']) && $options['print.lexer'] instanceof Lexer
                        ? $options['print.lexer']
                        : null,
                    $options
                )
            )
            ->withOperation(new Operation\Repl\ExitOperation());
    }

    /**
     * @return static
     */
    public function withMathFunctions(): self
    {
        return $this
            ->wrapPhpFunction('abs')
            ->wrapPhpFunction('acos')
            ->wrapPhpFunction('acosh')
            ->wrapPhpFunction('asin')
            ->wrapPhpFunction('asinh')
            ->wrapPhpFunction('atan2')
            ->wrapPhpFunction('atan')
            ->wrapPhpFunction('atanh')
            ->wrapPhpFunction('base_convert', 'base-convert')
            ->wrapPhpFunction('bindec')
            ->wrapPhpFunction('ceil')
            ->wrapPhpFunction('cos')
            ->wrapPhpFunction('cosh')
            ->wrapPhpFunction('decbin')
            ->wrapPhpFunction('dechex')
            ->wrapPhpFunction('decoct')
            ->wrapPhpFunction('deg2rad')
            ->wrapPhpFunction('exp')
            ->wrapPhpFunction('expm1')
            ->wrapPhpFunction('floor')
            ->wrapPhpFunction('fmod')
            ->wrapPhpFunction('getrandmax', 'rand-max')
            ->wrapPhpFunction('hexdec')
            ->wrapPhpFunction('hypot')
            ->wrapPhpFunction('intdiv')
            ->wrapPhpFunction('is_finite', 'finite?')
            ->wrapPhpFunction('is_infinite', 'infinite?')
            ->wrapPhpFunction('is_nan', 'nan?')
            ->wrapPhpFunction('lcg_value', 'lcg-value')
            ->wrapPhpFunction('log10')
            ->wrapPhpFunction('log1p')
            ->wrapPhpFunction('log')
            ->wrapPhpFunction('max')
            ->wrapPhpFunction('min')
            ->wrapPhpFunction('octdec')
            ->wrapPhpFunction('pi')
            ->wrapPhpFunction('pow')
            ->wrapPhpFunction('rad2deg')
            ->wrapPhpFunction('rand')
            ->wrapPhpFunction('round')
            ->wrapPhpFunction('sin')
            ->wrapPhpFunction('sinh')
            ->wrapPhpFunction('sqrt')
            ->wrapPhpFunction('srand', 'rand-seed')
            ->wrapPhpFunction('tan')
            ->wrapPhpFunction('tanh');
    }

    /**
     * @param Operation $operation
     * @return static
     */
    public function withOperation(Operation $operation): self
    {
        array_map(
            function (string $identifier) use ($operation) {
                $this->context->define($identifier, $operation);
            },
            $operation->getIdentifiers()
        );

        return $this;
    }

    /**
     * @param string $function
     * @param string|null $alias
     * @return Phlipy
     * @throws RuntimeException
     */
    public function wrapPhpFunction(string $function, string $alias = null): self
    {
        if (!function_exists($function)) {
            throw new RuntimeException("Undefined function '{$function}()'");
        }

        $this->context->define(
            $alias ?? $function,
            function () use ($function) {
                return call_user_func_array($function, func_get_args());
            }
        );

        return $this;
    }

    /**
     * @return static
     */
    public function withStringFunctions(): self
    {
        return $this
            ->wrapPhpFunction('crc32')
            ->wrapPhpFunction('explode', 'split')
            ->wrapPhpFunction('implode')
            ->wrapPhpFunction('md5')
            ->wrapPhpFunction('number_format', 'number-format')
            ->wrapPhpFunction('sha1')
            ->wrapPhpFunction('sprintf', 'string-format')
            ->wrapPhpFunction('str_pad', 'string-pad')
            ->wrapPhpFunction('str_repeat', 'string-repeat')
            ->wrapPhpFunction('str_replace', 'string-replace')
            ->wrapPhpFunction('mb_strtolower', 'lowercase')
            ->wrapPhpFunction('mb_strtoupper', 'uppercase')
            ->wrapPhpFunction('mb_substr', 'substring')
            ->wrapPhpFunction('ltrim')
            ->wrapPhpFunction('rtrim')
            ->wrapPhpFunction('chop')
            ->wrapPhpFunction('trim')
            ->wrapPhpFunction('wordwrap');
    }

    /**
     * @return ContextContract
     */
    public function getContext(): ContextContract
    {
        return $this->context;
    }
}

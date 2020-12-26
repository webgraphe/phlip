<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Closure;
use Throwable;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Exception\LexerException;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Operation\StandardOperation;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol;

class PrintOperation extends StandardOperation
{
    /** @var string */
    const IDENTIFIER = 'print';

    /** @var string */
    const OPTION_RETURN_TYPES = 'return-types';
    /** @var string */
    const OPTION_COLORS = 'colors';
    /** @var string */
    const OPTION_VERBOSE = 'verbose';

    /** @var string */
    const CLI_COLOR_TYPE = '1;30';
    /** @var string */
    const CLI_COLOR_EXCEPTION = '0;31';
    /** @var string */
    const CLI_COLOR_SYMBOL = '1;37';
    /** @var string */
    const CLI_COLOR_NUMBER = '1;36';
    /** @var string */
    const CLI_COLOR_STRING = '1;34';
    /** @var string */
    const CLI_COLOR_IDENTIFIER = '1;33';
    /** @var string */
    const CLI_COLOR_KEYWORD = '1;32';

    /** @var string[] */
    const CLI_LEXEME_COLORS = [
        Symbol::class => self::CLI_COLOR_SYMBOL,
        NumberAtom::class => self::CLI_COLOR_NUMBER,
        StringAtom::class => self::CLI_COLOR_STRING,
        IdentifierAtom::class => self::CLI_COLOR_IDENTIFIER,
        KeywordAtom::class => self::CLI_COLOR_KEYWORD,
    ];

    /** @var FormBuilder */
    private $formBuilder;

    /** @var bool[] */
    private $options = [
        self::OPTION_RETURN_TYPES => false,
        self::OPTION_COLORS => false,
        self::OPTION_VERBOSE => false,
    ];

    /** @var Lexer */
    private $lexer;

    public function __construct(
        FormBuilder $formBuilder = null,
        Lexer $lexer = null,
        array $options = []
    ) {
        foreach ($this->options as $key => $value) {
            $this->options[$key] = array_key_exists($key, $options) ? $options[$key] : $value;
        }

        $this->formBuilder = $formBuilder ?? new FormBuilder();
        $this->lexer = $lexer ?? new Lexer();
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param array ...$arguments
     * @return bool
     * @throws LexerException
     */
    public function __invoke(...$arguments): bool
    {
        $argument = $arguments ? $arguments[0] : null;
        $type = is_object($argument) ? $type = get_class($argument) : gettype($argument);
        $stackTraces = [];
        $color = self::CLI_COLOR_TYPE;
        $value = null;

        if ($argument instanceof Throwable) {
            $color = self::CLI_COLOR_EXCEPTION;
            if ($previous = $argument->getPrevious()) {
                call_user_func($this, $previous);
            }
            $value = $argument->getMessage();
            if ($this->options[self::OPTION_VERBOSE]) {
                if ($argument instanceof ProgramException) {
                    $stackTraces[] = $this->dumpProgramExceptionStackTrace($argument);
                }
                $stackTraces[] = "PHP Stack Trace:" . PHP_EOL . $argument->getTraceAsString();
            }
        } else {
            try {
                $form = $this->formBuilder->asForm(new Context(), $argument);
                $value = $this->stringifyLexemeStream($this->lexer->parseSource((string)$form));
            } catch (Throwable $t) {
                // do nothing
            }
        }

        $output = '';
        if ($this->options[self::OPTION_RETURN_TYPES]) {
            $output .= ($this->options[self::OPTION_COLORS] ? "\033[{$color}m{$type}\033[0m" : $type) . PHP_EOL;
        }

        // The value could be "0"
        if (strlen($value = trim($value))) {
            $output .= $value . PHP_EOL;
        }

        if ($stackTraces) {
            $output .= PHP_EOL . implode(PHP_EOL . PHP_EOL, $stackTraces) . PHP_EOL;
        }

        echo $output . PHP_EOL;

        return true;
    }

    protected function stringifyLexemeStream(LexemeStream $stream): string
    {
        if ($this->options[self::OPTION_COLORS]) {
            return $stream->withLexemeStylizer(static::cliColors());
        }

        return $stream;
    }

    public static function cliColors(): Closure
    {
        return function (LexemeContract $lexeme): string {
            $class = get_class($lexeme);

            return isset(self::CLI_LEXEME_COLORS[$class])
                ? "\033[" . self::CLI_LEXEME_COLORS[$class] . 'm' . $lexeme . "\033[0m"
                : (string)$lexeme;
        };
    }

    /**
     * @param ProgramException $exception
     * @return string
     * @throws LexerException
     */
    protected function dumpProgramExceptionStackTrace(ProgramException $exception): string
    {
        $stack = [];
        $context = $exception->getContext();
        while ($context) {
            $forms = $exception->getContext()->getFormStack();
            while ($forms) {
                $stack[] = $this->stringifyLexemeStream($this->lexer->parseSource((string)array_pop($forms)));
            }
            $context = $context->getParent();
        }

        return 'Phlip Stack Trace:'
            . PHP_EOL
            . implode(
                PHP_EOL,
                array_map(
                    function ($key, $value) {
                        return '#' . $key . ' ' . $value;
                    },
                    array_keys($stack),
                    $stack
                )
            );
    }

    /**
     * @return FormBuilder
     */
    public function getFormBuilder(): FormBuilder
    {
        return $this->formBuilder;
    }

    /**
     * @return Lexer
     */
    public function getLexer(): Lexer
    {
        return $this->lexer;
    }

    /**
     * @return bool[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}

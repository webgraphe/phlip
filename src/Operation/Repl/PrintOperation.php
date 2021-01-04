<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Closure;
use Throwable;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Operation\ManualOperation;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol;
use Webgraphe\Phlip\System;

/**
 * Prints a form given
 */
class PrintOperation extends ManualOperation
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
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return bool
     */
    protected function invoke(ScopeContract $scope, FormList $forms): bool
    {
        try {
            $argument = $scope->execute($forms->assertHead());
        } catch (Throwable $t) {
            $argument = $t;
        }

        $type = is_object($argument) ? $type = get_class($argument) : gettype($argument);
        $color = self::CLI_COLOR_TYPE;
        $value = null;

        if ($argument instanceof Throwable) {
            $color = self::CLI_COLOR_EXCEPTION;
            while ($argument) {
                $value = [];
                $value[] = $argument->getMessage();
                if ($this->options[self::OPTION_VERBOSE]) {
                    $value[] = '    ' . implode(PHP_EOL . '    ', System::backtrace($argument->getTrace()));
                }
                $argument = $argument->getPrevious();
                $this->output($type, implode(PHP_EOL, $value), $color);
            }
        } else {
            try {
                $form = $this->formBuilder->asForm($argument);
                $value = $this->stringifyLexemeStream($this->lexer->parseSource((string)$form));
            } catch (Throwable $t) {
                // do nothing
            }
            $this->output($type, $value, $color);
        }

        $tail = $forms->getTail();

        return $tail->isEmpty()
            ? true
            : $this->invoke($scope, $tail);
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
                ? "\e[" . self::CLI_LEXEME_COLORS[$class] . 'm' . $lexeme . "\e[0m"
                : (string)$lexeme;
        };
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

    private function output(string $type, ?string $value, string $typeColor)
    {
        $output = '';
        if ($this->options[self::OPTION_RETURN_TYPES]) {
            $output .= ($this->options[self::OPTION_COLORS] ? "\e[{$typeColor}m{$type}\e[0m" : $type) . PHP_EOL;
        }

        if (strlen($value = trim($value))) {
            $output .= $value . PHP_EOL;
        }

        echo $output;
    }
}

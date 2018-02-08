<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Atom\KeywordAtom;
use Webgraphe\Phlip\Atom\NumberAtom;
use Webgraphe\Phlip\Atom\StringAtom;
use Webgraphe\Phlip\Contracts\LexemeContract;
use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Operation\StandardOperation;
use Webgraphe\Phlip\Stream\LexemeStream;
use Webgraphe\Phlip\Symbol;

class PrintOperation extends StandardOperation
{
    const IDENTIFIER = 'print';

    /** @var string */
    const OPTION_RETURN_TYPE = 'return-types';
    /** @var string */
    const OPTION_COLORS = 'colors';
    /** @var string */
    const OPTION_VERBOSE = 'verbose';
    /** @var string */
    const OPTION_JSON_ALIKE = 'json-alike';

    /** @var FormBuilder */
    private $formBuilder;
    /** @var array */
    private $options = [
        self::OPTION_RETURN_TYPE => false,
        self::OPTION_COLORS => false,
        self::OPTION_VERBOSE => false,
        self::OPTION_JSON_ALIKE => false,
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

        $this->formBuilder = $formBuilder ?? new FormBuilder;
        $this->lexer = $lexer ?? new Lexer;
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
     * @return mixed
     * @throws \Webgraphe\Phlip\Exception\LexerException
     */
    public function __invoke(...$arguments)
    {
        $argument = $arguments ? $arguments[0] : null;

        $color = '1;30';
        $type = gettype($argument);
        $extras = [];
        if ($argument instanceof \Throwable) {
            if ($previous = $argument->getPrevious()) {
                call_user_func($this, $previous);
            }
            $type = get_class($argument);
            $subject = $argument->getMessage();
            if ($argument instanceof ProgramException) {
                $stack = [];
                $context = $argument->getContext();
                while ($context) {
                    $forms = $argument->getContext()->getFormStack();
                    while ($forms) {
                        $stack[] = $this->stringifyLexemeStream($this->lexer->parseSource((string)array_pop($forms)));
                    }
                    $context = $context->getParent();
                }
                $extras[] = 'Phlip Stack Trace:'
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
            if ($this->options[self::OPTION_VERBOSE]) {
                $extras[] = "PHP Stack Trace:" . PHP_EOL . $argument->getTraceAsString();
            }
            $color = '0;31';
        } else {
            if (is_object($argument)) {
                $type = get_class($argument);
            }

            try {
                $form = $this->formBuilder->asForm($argument);
                $subject = $this->stringifyLexemeStream($this->lexer->parseSource((string)$form));
            } catch (\Throwable $t) {
                $subject = '';
            }
        }

        $output = '';
        if ($this->options[self::OPTION_RETURN_TYPE] && $type) {
            $output .= ($this->options[self::OPTION_COLORS] ? "\033[{$color}m{$type}\033[0m" : $type) . PHP_EOL;
        }

        if (strlen($subject = trim($subject))) {
            $output .= $subject . PHP_EOL;
        }

        if ($extras) {
            $output .= PHP_EOL . implode(PHP_EOL . PHP_EOL, $extras) . PHP_EOL;
        }

        $output .= PHP_EOL;

        echo $output;

        return true;
    }

    private function stringifyLexemeStream(LexemeStream $stream): string
    {
        if ($this->options[self::OPTION_JSON_ALIKE]) {
            $stream = $stream->jsonAlike();
        }

        return (string)$stream->withLexemeStylizer(
            $this->options[self::OPTION_COLORS]
                ? self::cliColors()
                : self::cliPlain()
        );
    }

    public static function cliPlain(): \Closure
    {
        return function (LexemeContract $lexeme): string {
            return (string)$lexeme;
        };
    }

    public static function cliColors(): \Closure
    {
        return function (LexemeContract $lexeme): string {
            if ($lexeme instanceof Symbol) {
                return "\033[1;37m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof NumberAtom) {
                return "\033[1;36m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof StringAtom) {
                return "\033[1;34m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof IdentifierAtom) {
                return "\033[1;33m{$lexeme}\033[0m";
            }

            if ($lexeme instanceof KeywordAtom) {
                return "\033[1;32m{$lexeme}\033[0m";
            }

            return (string)$lexeme;
        };
    }
}

<?php

namespace Webgraphe\Phlip\Operation\Repl;

use Webgraphe\Phlip\Exception\ProgramException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Operation\StandardOperation;
use Webgraphe\Phlip\Stylizer;

class PrintOperation extends StandardOperation
{
    const IDENTIFIER = 'print';

    /** @var string */
    const OPTION_VERBOSE = 'verbose';

    /** @var Stylizer */
    private $stylizer;
    /** @var FormBuilder */
    private $formBuilder;
    /** @var array */
    private $options = [
        self::OPTION_VERBOSE => false,
    ];
    /** @var Lexer */
    private $lexer;

    public function __construct(
        Stylizer $stylizer = null,
        FormBuilder $formBuilder = null,
        Lexer $lexer = null,
        array $options = []
    ) {
        $this->stylizer = $stylizer ?? new Stylizer;
        $this->formBuilder = $formBuilder ?? new FormBuilder;
        $this->lexer = $lexer ?? new Lexer;

        foreach ($this->options as $key => $value) {
            $this->options[$key] = array_key_exists($key, $options) ? $options[$key] : $value;
        }
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
     * @throws \Webgraphe\Phlip\Exception\StreamException
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
                        $stack[] = $this->stylizer->stylizeSource((string)array_pop($forms));
                    }
                    $context = $context->getParent();
                }
                $extras[] = 'Phlip Call Stack:' . PHP_EOL . implode(PHP_EOL, $stack);
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
                $subject = $this->stylizer->stylizeLexemeStream($this->lexer->parseSource((string)$form));
            } catch (\Throwable $t) {
                $subject = '';
            }
        }

        $output = '';
        if ($type) {
            $output .= "\033[{$color}m{$type}\033[0m" . PHP_EOL;
        }

        if (strlen($subject = trim($subject))) {
            $output .=  $subject . PHP_EOL;
        }

        if ($extras) {
            $output .= PHP_EOL . implode(PHP_EOL . PHP_EOL, $extras) . PHP_EOL;
        }

        $output .= PHP_EOL;

        echo $output;

        return true;
    }
}

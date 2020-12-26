<?php

namespace Webgraphe\Phlip\Tests\Unit;

use RuntimeException;
use Throwable;
use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormBuilder;
use Webgraphe\Phlip\Lexer;
use Webgraphe\Phlip\Operation\LanguageConstruct\WhileOperation;
use Webgraphe\Phlip\Operation\Repl\PrintOperation;
use Webgraphe\Phlip\Operation\Repl\ReadOperation;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class PhlipyTest extends TestCase
{
    /**
     * @dataProvider everythingData
     * @param array $options
     * @throws ContextException
     */
    public function testEverything(array $options = [])
    {
        $this->assertInstanceOf(Context::class, $context = Phlipy::active()->withRepl($options)->getContext());

        /** @var ReadOperation $read */
        $read = $context->get('read');
        $this->assertInstanceOf(ReadOperation::class, $read);

        $this->assertEquals($read->isMultiLine(), !empty($options['read.multi-line']));
        if ($prompt = ($options['read.prompt'] ?? null)) {
            $this->assertEquals($read->getPrompt(), $prompt);
        }

        /** @var PrintOperation $print */
        $print = $context->get('print');
        $this->assertInstanceOf(PrintOperation::class, $print);
        if ($formBuilder = ($options['print.form-builder'] ?? null)) {
            $this->assertEquals($print->getFormBuilder(), $formBuilder);
        }
        if ($lexer = ($options['print.lexer'] ?? null)) {
            $this->assertEquals($print->getLexer(), $lexer);
        }
        $this->assertEquals(
            [
                ($returnTypes = PrintOperation::OPTION_RETURN_TYPES) => !empty($options["print.{$returnTypes}"]),
                ($colors = PrintOperation::OPTION_COLORS) => !empty($options["print.{$colors}"]),
                ($verbose = PrintOperation::OPTION_VERBOSE) => !empty($options["print.{$verbose}"]),
            ],
            $print->getOptions()
        );

        $loop = $context->get($options['loop.identifier'] ?? Phlipy::LOOP_IDENTIFIER);
        $this->assertInstanceOf(WhileOperation::class, $loop);
    }

    public static function everythingData(): array
    {
        return [
            'default' => [],
            'with all options' => [
                'options' => [
                    'read.multi-line' => true,
                    'read.prompt' => function () {
                        return "custom prompt > ";
                    },
                    'print.form-builder' => new FormBuilder(),
                    'print.lexer' => new Lexer(),
                    'loop.identifier' => 'my-loop',
                ],
            ],
        ];
    }

    public function testUserErrors()
    {
        $context = Phlipy::active()->getContext();
        $code = <<<CODE
(notice "a notification")
(warning "a warning")
(error "an error")
(deprecated "a deprecation")
CODE;

        $errors = [];

        set_error_handler(
            function ($no, $str) use (&$errors) {
                $errors[] = [$no, $str];

                return true;
            }
        );

        try {
            Program::parse($code)->execute($context);
        } catch (Throwable $t) {
            $this->fail($t->getMessage());
        } finally {
            restore_error_handler();
        }

        $this->assertEquals(
            [
                [E_USER_NOTICE, "a notification"],
                [E_USER_WARNING, "a warning"],
                [E_USER_ERROR, "an error"],
                [E_USER_DEPRECATED, "a deprecation"],
            ],
            $errors
        );
    }

    public function testWrapUndefinedPhpFunction()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Undefined function 'not a function()'");

        (new Phlipy(new Context()))->wrapPhpFunction('not a function');
    }

    public function testActiveInterop()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Context must be PHP Interoperable to support PHP interop operations");

        Phlipy::active(new Context());
    }
}

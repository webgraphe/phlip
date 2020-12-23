<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Throwable;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Traits\AssertsClasses;

class StaticOperation extends PhpInteroperableOperation
{
    use AssertsClasses;

    public const IDENTIFIER = '::';

    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed|void
     * @throws AssertionException
     * @throws ContextException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $class = $this->assertClassEnabled(
            $this->assertPhpInteroperableContext($context, static::class),
            IdentifierAtom::assertStaticType($forms->assertHead())->getValue()
        );

        $tail = $forms->getTail();
        $member = IdentifierAtom::assertStaticType($tail->assertHead())->getValue();
        if (method_exists($class, $member)) {
            if (!is_callable($callable = [$class, $member])) {
                throw new AssertionException("Call to non-public static method '{$class}::{$member}()'");
            }

            return call_user_func(
                [$class, $member],
                ...array_map(
                    function (FormContract $form) use ($context) {
                        return $form->evaluate($context);
                    },
                    $tail->getTail()->all()
                )
            );
        }

        if ('-' === substr($member, 0, 1)) {
            $member = substr($member, 1);
        }

        if (defined("{$class}::{$member}")) {
            return constant("{$class}::{$member}");
        }

        if ('$' === substr($member, 0, 1)) {
            $field = substr($member, 1);
            if (property_exists($class, $field)) {
                try {
                    return $class::$$field;
                } catch (Throwable $t) {
                    throw new AssertionException("Cannot access non-public field '{$class}::{$member}'");
                }
            }
        }

        // TODO Differentiate between non-existent and non-public
        throw new AssertionException("Undefined public field '{$class}::{$member}'");
    }
}

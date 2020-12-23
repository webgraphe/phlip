<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Traits\AssertsClasses;

class ObjectOperation extends PhpInteroperableOperation
{
    use AssertsClasses;

    public const IDENTIFIER = '->';

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
        $object = $forms->assertHead()->evaluate($context);
        $identifier = is_object($object) ? get_class($object) : gettype($object);
        $this->assertClassEnabled($this->assertPhpInteroperableContext($context, static::class), $object);

        $tail = $forms->getTail();
        $member = IdentifierAtom::assertStaticType($tail->assertHead())->getValue();
        if (method_exists($object, $member)) {
            if (!is_callable($callable = [$object, $member])) {
                throw new AssertionException("Call to non-public instance method '{$identifier}->{$member}()'");
            }

            return call_user_func(
                [$object, $member],
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

        if (property_exists($object, $member)
            || (method_exists($object, 'offsetExists') && $object->offsetExists($member))
        ) {
            return $object->$member;
        }

        throw new AssertionException("Undefined public field '{$identifier}->{$member}'");
    }
}

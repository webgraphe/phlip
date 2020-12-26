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
use Webgraphe\Phlip\Traits\AssertsObjects;

class ObjectOperation extends PhpInteroperableOperation
{
    use AssertsClasses,
        AssertsObjects;

    /** @var string */
    public const IDENTIFIER = '->';

    /**
     * @return string[]
     */
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
        $object = static::assertObject($context->execute($forms->assertHead()));
        $identifier = is_object($object) ? get_class($object) : gettype($object);
        static::assertClassEnabled($this->assertPhpInteroperableContext($context, static::class), $object);

        $tail = $forms->getTail();
        $member = IdentifierAtom::assertStaticType($tail->assertHead())->getValue();
        if (method_exists($object, $member)) {
            if (!is_callable($callable = [$object, $member])) {
                throw new AssertionException("Cannot call '{$identifier}->{$member}()'");
            }

            return call_user_func(
                [$object, $member],
                ...array_map(
                    function (FormContract $form) use ($context) {
                        return $context->execute($form);
                    },
                    $tail->getTail()->all()
                )
            );
        }

        if ($tail->getTail()->count()) {
            throw new AssertionException("Cannot call undefined '{$identifier}->{$member}()'");
        }

        if ('-' === substr($member, 0, 1)) {
            $member = substr($member, 1);
        }

        try {
            return $object->$member;
        } catch (Throwable $t) {
            throw new AssertionException("Cannot access '{$identifier}->{$member}'", 0, $t);
        }
    }

    /**
     * @param object $object
     * @param string $member
     * @param mixed $value
     * @return mixed
     * @throws AssertionException
     */
    public function assign(object $object, string $member, $value)
    {
        try {
            return $object->{$member} = $value;
        } catch (Throwable $t) {
            $class = get_class($object);

            throw new AssertionException("Cannot access '{$class}->{$member}'", 0, $t);
        }
    }
}

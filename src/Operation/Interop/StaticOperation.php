<?php

namespace Webgraphe\Phlip\Operation\Interop;

use ReflectionClass;
use ReflectionException;
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

    /** @var string */
    public const IDENTIFIER = '::';

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
     * @throws ReflectionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $class = static::assertClassEnabled(
            $this->assertPhpInteroperableContext($context, static::class),
            IdentifierAtom::assertStaticType($forms->assertHead())->getValue()
        );

        $tail = $forms->getTail();
        $member = IdentifierAtom::assertStaticType($tail->assertHead())->getValue();
        if (method_exists($class, $member)) {
            if (!is_callable($callable = [$class, $member])) {
                throw new AssertionException("Cannot call '{$class}::{$member}()'");
            }

            return call_user_func(
                [$class, $member],
                ...array_map(
                    function (FormContract $form) use ($context) {
                        return $context->execute($form);
                    },
                    $tail->getTail()->all()
                )
            );
        }

        if (count($tail->getTail())) {
            throw new AssertionException("Cannot call undefined '{$class}::{$member}()'");
        }

        $reflectionClass = new ReflectionClass($class);
        if ('$' === substr($member, 0, 1)) {
            return $this->getPropertyValue($reflectionClass, substr($member, 1));
        }

        if ('-' === substr($member, 0, 1)) {
            $member = substr($member, 1);
        }

        return $this->getConstantValue($reflectionClass, $member);
    }

    /**
     * @param string $class
     * @param string $member
     * @param mixed $value
     * @return mixed
     * @throws AssertionException
     */
    public function assign(string $class, string $member, $value)
    {
        try {
            return $class::$$member = $value;
        } catch (Throwable $t) {
            throw new AssertionException("Cannot access '{$class}::{$member}'", 0, $t);
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $field
     * @return mixed
     * @throws AssertionException
     */
    private function getPropertyValue(ReflectionClass $reflectionClass, string $field)
    {
        $class = $reflectionClass->getName();
        $member = "\${$field}";

        try {
            $reflectionProperty = $reflectionClass->getProperty($field);
        } catch (Throwable $t) {
            throw new AssertionException("Cannot access undefined '{$class}::{$member}'", 0, $t);
        }

        if (!$reflectionProperty->isStatic() || !$reflectionProperty->isPublic()) {
            throw new AssertionException("Cannot access '{$class}::{$member}'");
        }

        return $reflectionProperty->getValue();
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $field
     * @return mixed
     * @throws AssertionException
     */
    private function getConstantValue(ReflectionClass $reflectionClass, string $field)
    {
        $class = $reflectionClass->getName();

        if ($reflectionClassConstant = $reflectionClass->getReflectionConstant($field)) {
            if (!$reflectionClassConstant->isPublic()) {
                throw new AssertionException("Cannot access non-public '{$class}::{$field}'");
            }

            return $reflectionClassConstant->getValue();
        }

        throw new AssertionException("Cannot access undefined '{$class}::{$field}'");
    }
}

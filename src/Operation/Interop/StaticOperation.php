<?php

namespace Webgraphe\Phlip\Operation\Interop;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionProperty;
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

        if (count($tail) > 1) {
            throw new AssertionException("Cannot call undefined '{$class}::{$member}()'");
        }

        $reflectionClass = new ReflectionClass($class);

        return '$' === substr($member, 0, 1)
            ? $this->getPropertyValue($reflectionClass, $member)
            : $this->getConstantValue($reflectionClass, $member);
    }

    /**
     * @param string $class
     * @param string $member
     * @param mixed $value
     * @return mixed
     * @throws AssertionException
     * @throws ReflectionException
     */
    public function assignPropertyValue(string $class, string $member, $value)
    {
        $this->getPropertyReflection(new ReflectionClass($class), $member)->setValue($value);

        return $value;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $member
     * @return mixed
     * @throws AssertionException
     */
    private function getPropertyValue(ReflectionClass $reflectionClass, string $member)
    {
        return $this->getPropertyReflection($reflectionClass, $member)->getValue();
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $member
     * @return mixed
     * @throws AssertionException
     */
    private function getConstantValue(ReflectionClass $reflectionClass, string $member)
    {
        return $this->getConstantReflection($reflectionClass, $member)->getValue();
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $member
     * @return ReflectionProperty
     * @throws AssertionException
     */
    private function getPropertyReflection(ReflectionClass $reflectionClass, string $member): ReflectionProperty
    {
        $class = $reflectionClass->getName();
        if (!strlen($member) || '$' !== $member[0]) {
            throw new AssertionException("Invalid static field identifier '{$class}::{$member}'; must be prefixed by '$'");
        }

        try {
            $reflectionProperty = $reflectionClass->getProperty(substr($member, 1));
        } catch (Throwable $t) {
            throw new AssertionException("Cannot access undefined '{$class}::{$member}'", 0, $t);
        }

        if (!$reflectionProperty->isPublic()) {
            throw new AssertionException("Cannot access non-public '{$class}::{$member}'");
        }

        if (!$reflectionProperty->isStatic()) {
            throw new AssertionException("Cannot access non-static '{$class}::{$member}'");
        }

        return $reflectionProperty;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $member
     * @return ReflectionClassConstant
     * @throws AssertionException
     */
    private function getConstantReflection(ReflectionClass $reflectionClass, string $member): ReflectionClassConstant
    {
        $class = $reflectionClass->getName();

        if ('-' === substr($member, 0, 1)) {
            $member = substr($member, 1);
        }

        if ($reflectionClassConstant = $reflectionClass->getReflectionConstant($member)) {
            if (!$reflectionClassConstant->isPublic()) {
                throw new AssertionException("Cannot access non-public '{$class}::{$member}'");
            }

            return $reflectionClassConstant;
        }

        throw new AssertionException("Cannot access undefined '{$class}::{$member}'");

    }
}

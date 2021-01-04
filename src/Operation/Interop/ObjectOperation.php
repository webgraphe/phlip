<?php

namespace Webgraphe\Phlip\Operation\Interop;

use ReflectionObject;
use ReflectionProperty;
use Throwable;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormCollection\FormList;
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
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed|void
     * @throws AssertionException
     * @throws ScopeException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $object = static::assertObject($scope->execute($forms->assertHead()));
        $identifier = is_object($object) ? get_class($object) : gettype($object);
        static::assertClassEnabled($this->assertPhpInteroperableScope($scope, static::class), $object);
        $tail = $forms->getTail();
        $member = IdentifierAtom::assertStaticType($tail->assertHead())->getValue();

        if (method_exists($object, $member)) {
            if (!is_callable($callable = [$object, $member])) {
                throw new AssertionException("Cannot call non-public '{$identifier}->{$member}()'");
            }

            return call_user_func(
                [$object, $member],
                ...array_map(
                    function (FormContract $form) use ($scope) {
                        return $scope->execute($form);
                    },
                    $tail->getTail()->all()
                )
            );
        }

        if (count($tail) > 1) {
            throw new AssertionException("Cannot call undefined '{$identifier}->{$member}()'");
        }

        if ('-' === substr($member, 0, 1)) {
            $member = substr($member, 1);
        }

        return $this->getPropertyValue($object, $member);
    }

    /**
     * @param object $object
     * @param string $member
     * @param mixed $value
     * @return mixed
     * @throws AssertionException
     */
    public function assignPropertyValue(object $object, string $member, $value)
    {
        $this->getReflectionProperty($object, $member)->setValue($object, $value);

        return $value;
    }

    /**
     * @param object $object
     * @param string $member
     * @return mixed
     * @throws AssertionException
     */
    private function getPropertyValue(object $object, string $member)
    {
        return $this->getReflectionProperty($object, $member)->getValue($object);
    }

    /**
     * @param object $object
     * @param string $member
     * @return ReflectionProperty
     * @throws AssertionException
     */
    private function getReflectionProperty(object $object, string $member): ReflectionProperty
    {
        $reflectionObject = new ReflectionObject($object);
        $identifier = get_class($object);

        try {
            $reflectionProperty = $reflectionObject->getProperty($member);
        } catch (Throwable $t) {
            throw new AssertionException("Cannot access undefined '{$identifier}->{$member}'", 0, $t);
        }

        if (!$reflectionProperty->isPublic()) {
            throw new AssertionException("Cannot access non-public '{$identifier}->{$reflectionProperty->getName()}'");
        }

        if ($reflectionProperty->isStatic()) {
            throw new AssertionException(
                "Cannot access '{$identifier}->{$reflectionProperty->getName()}' as non-static"
            );
        }

        return $reflectionProperty;
    }
}

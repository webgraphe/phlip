<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use ReflectionException;
use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\Interop\ObjectOperation;
use Webgraphe\Phlip\Operation\Interop\StaticOperation;
use Webgraphe\Phlip\Operation\ManualOperation;
use Webgraphe\Phlip\Traits\AssertsObjects;

class SetOperation extends ManualOperation
{
    use AssertsObjects;

    const IDENTIFIER = 'set!';

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     * @throws ReflectionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $expression = $forms->getHead();

        if ($expression instanceof FormList) {
            $head = $scope->execute($expression->assertHead());
            $value = $scope->execute($forms->assertTailHead());
            $tail = $expression->getTail();
            $member = IdentifierAtom::assertStaticType($tail->assertTailHead())->getValue();

            if ($head instanceof ObjectOperation) {
                return $head->assignPropertyValue(
                    static::assertObject($scope->execute($tail->assertHead())),
                    $member,
                    $value
                );
            }

            if ($head instanceof StaticOperation) {
                return $head->assignPropertyValue(
                    IdentifierAtom::assertStaticType($tail->assertHead())->getValue(),
                    $member,
                    $value
                );
            }

            throw new AssertionException("Malformed interoperable set!");
        }

        if ($expression instanceof IdentifierAtom) {
            return $scope->set(
                $expression->getValue(),
                $scope->execute($forms->assertTailHead())
            );
        }

        throw new AssertionException('Malformed variable set!');
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

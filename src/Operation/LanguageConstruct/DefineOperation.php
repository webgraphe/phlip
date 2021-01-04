<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Operation\ManualOperation;

class DefineOperation extends ManualOperation
{
    const IDENTIFIER = 'define';

    /**
     * @param ScopeContract $scope
     * @param FormList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ScopeContract $scope, FormList $forms)
    {
        $variable = $forms->assertHead();

        if ($variable instanceof FormList) {
            $name = IdentifierAtom::assertStaticType($variable->assertHead());

            return $scope->define(
                $name->getValue(),
                LambdaOperation::invokeStatic(
                    $scope,
                    $variable->getTail(),
                    ...$forms->getTail()
                )
            );
        }

        if ($variable instanceof IdentifierAtom) {
            return $scope->define(
                $variable->getValue(),
                $scope->execute($forms->assertTailHead())
            );
        }

        throw new AssertionException('Malformed define');
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

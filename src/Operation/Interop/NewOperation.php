<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ScopeException;
use Webgraphe\Phlip\FormCollection\FormList;
use Webgraphe\Phlip\Traits\AssertsClasses;

class NewOperation extends PhpInteroperableOperation
{
    use AssertsClasses;

    /** @var string */
    public const IDENTIFIER = 'new';

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
     * @return object
     * @throws AssertionException
     * @throws ScopeException
     */
    protected function invoke(ScopeContract $scope, FormList $forms): object
    {
        $class = static::assertClassEnabled(
            $this->assertPhpInteroperableScope($scope, static::class),
            IdentifierAtom::assertStaticType($forms->assertHead())->getValue()
        );

        return new $class(
            ...array_map(
                   function (FormContract $form) use ($scope) {
                       return $scope->execute($form);
                   },
                   $forms->getTail()->all()
               )
        );
    }
}

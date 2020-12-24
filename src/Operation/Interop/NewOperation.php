<?php

namespace Webgraphe\Phlip\Operation\Interop;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
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
     * @param ContextContract $context
     * @param ProperList $forms
     * @return object
     * @throws AssertionException
     * @throws ContextException
     */
    protected function invoke(ContextContract $context, ProperList $forms): object
    {
        $class = static::assertClassEnabled(
            $this->assertPhpInteroperableContext($context, static::class),
            IdentifierAtom::assertStaticType($forms->assertHead())->getValue()
        );

        return new $class(
            ...array_map(
                   function (FormContract $form) use ($context) {
                       return $form->evaluate($context);
                   },
                   $forms->getTail()->all()
               )
        );
    }
}

<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\ManualOperation;

class DefineOperation extends ManualOperation
{
    const IDENTIFIER = 'define';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws AssertionException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $variable = $forms->assertHead();

        if ($variable instanceof ProperList) {
            $name = IdentifierAtom::assertStaticType($variable->assertHead());

            return $context->define(
                $name->getValue(),
                LambdaOperation::invokeStatic(
                    $context,
                    $variable->getTail(),
                    ...$forms->getTail()
                )
            );
        }

        if ($variable instanceof IdentifierAtom) {
            return $context->define(
                $variable->getValue(),
                $context->execute($forms->assertTailHead())
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

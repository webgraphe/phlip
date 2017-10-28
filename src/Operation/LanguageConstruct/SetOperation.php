<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class SetOperation extends PrimaryOperation
{
    const IDENTIFIER = 'set';

    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $variable = $forms->getHead();

        if ($variable instanceof ProperList) {
            $name = IdentifierAtom::assertStaticType($variable->getHead());

            return $context->set(
                $name->getValue(),
                LambdaOperation::invokeStatic(
                    $context,
                    $variable->getTail(),
                    ...$forms->getTail()
                )
            );
        }

        if ($variable instanceof IdentifierAtom) {
            return $context->set(
                $variable->getValue(),
                $context->execute($forms->getTail()->assertHead())
            );
        }

        throw new AssertionException('Malformed set');
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

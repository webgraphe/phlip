<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\ContextException;
use Webgraphe\Phlip\FormCollection\ProperList;
use Webgraphe\Phlip\Operation\Interop\ObjectOperation;
use Webgraphe\Phlip\Operation\Interop\StaticOperation;
use Webgraphe\Phlip\Operation\PrimaryOperation;
use Webgraphe\Phlip\Traits\AssertsObjects;

class SetOperation extends PrimaryOperation
{
    use AssertsObjects;

    const IDENTIFIER = 'set!';

    /**
     * @param ContextContract $context
     * @param ProperList $forms
     * @return mixed
     * @throws AssertionException
     * @throws ContextException
     */
    protected function invoke(ContextContract $context, ProperList $forms)
    {
        $expression = $forms->getHead();

        if ($expression instanceof ProperList) {
            $head = $expression->assertHead()->evaluate($context);
            $value = $forms->getTail()->assertHead()->evaluate($context);
            $tail = $expression->getTail();
            $member = IdentifierAtom::assertStaticType($tail->getTail()->assertHead())->getValue();

            if ($head instanceof ObjectOperation) {
                return $head->assign(static::assertObject($tail->assertHead()->evaluate($context)), $member, $value);
            }

            if ($head instanceof StaticOperation) {
                return $head->assign(
                    IdentifierAtom::assertStaticType($tail->assertHead())->getValue(),
                    $member,
                    $value
                );
            }

            throw new AssertionException("Malformed interoperable set!");
        }

        if ($expression instanceof IdentifierAtom) {
            return $context->set(
                $expression->getValue(),
                $context->execute($forms->getTail()->assertHead())
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

<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct\Structures;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryFunction;

class DictionaryOperation extends PrimaryFunction
{
    const IDENTIFIER = 'dictionary';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $dictionary = (object)[];

        while ($head = $expressions->getHeadExpression()) {
            $expressions = $expressions->getTailExpressions();
            $head = ExpressionList::assertStaticType($head);
            $name = $head->assertHeadExpression()->evaluate($context);
            $value = $head->getTailExpressions()->assertHeadExpression()->evaluate($context);
            switch (true) {
                case $name instanceof IdentifierAtom:
                    $dictionary->{$name->getValue()} = $value;
                    break;
                case is_scalar($name):
                    $dictionary->{$name} = $value;
                    break;
                default:
                    throw new \RuntimeException("Malformed dictionary");
            }
        }

        return $dictionary;
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

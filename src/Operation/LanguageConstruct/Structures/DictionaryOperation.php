<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct\Structures;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct;

class DictionaryOperation extends LanguageConstruct
{
    const IDENTIFIER = 'dictionary';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $dictionary = [];

        while ($head = $expressions->getHeadExpression()) {
            $expressions = $expressions->getTailExpressions();
            $head = ExpressionList::assertStaticType($head);
            $name = $head->getHeadExpression();
            $value = $head->getTailExpressions()->assertHeadExpression()->evaluate($context);
            switch (true) {
                case $name instanceof IdentifierAtom:
                    $dictionary[$name->getValue()] = $value;
                    break;
                case $name instanceof ExpressionList:
                    $dictionary[$name->evaluate($context)] = $value;
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

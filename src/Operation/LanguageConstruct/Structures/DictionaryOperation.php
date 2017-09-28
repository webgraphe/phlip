<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct\Structures;

use Webgraphe\Phlip\Atom\IdentifierAtom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class DictionaryOperation extends PrimaryOperation
{
    const IDENTIFIER = 'dictionary';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     * @throws EvaluationException
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $dictionary = (object)[];

        while ($head = $expressions->getHeadExpression()) {
            $expressions = $expressions->getTailExpressions();
            $head = ExpressionList::assertStaticType($head);
            $nameExpression = $head->assertHeadExpression();
            $name = $nameExpression->evaluate($context);
            $value = $head->getTailExpressions()->assertHeadExpression()->evaluate($context);
            switch (true) {
                case is_scalar($name):
                    $dictionary->{$name} = $value;
                    break;
                default:
                    throw EvaluationException::fromExpression($nameExpression, "Malformed dictionary");
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

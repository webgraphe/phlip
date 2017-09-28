<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct\Structures;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\FormList;
use Webgraphe\Phlip\Operation\PrimaryOperation;

class DictionaryOperation extends PrimaryOperation
{
    const IDENTIFIER = 'dictionary';

    /**
     * @param ContextContract $context
     * @param FormList $expressions
     * @return mixed
     * @throws EvaluationException
     */
    protected function invoke(ContextContract $context, FormList $expressions)
    {
        $dictionary = (object)[];

        while ($head = $expressions->getHead()) {
            $expressions = $expressions->getTail();
            $head = FormList::assertStaticType($head);
            $nameExpression = $head->assertHead();
            $name = $nameExpression->evaluate($context);
            $value = $head->getTail()->assertHead()->evaluate($context);
            switch (true) {
                case is_scalar($name):
                    $dictionary->{$name} = $value;
                    break;
                default:
                    throw EvaluationException::fromForm($nameExpression, "Malformed dictionary");
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

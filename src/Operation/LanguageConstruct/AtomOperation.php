<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use Webgraphe\Phlip\Atom;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct;
use Webgraphe\Phlip\QuotedExpression;

class AtomOperation extends LanguageConstruct
{
    const IDENTIFIER = 'atom?';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $variable = $expressions->assertHeadExpression();

        if ($variable instanceof QuotedExpression) {
            $variable = $variable->getExpression();
        }

        return $variable instanceof Atom || $variable instanceof ExpressionList && 0 === count($variable);
    }

    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

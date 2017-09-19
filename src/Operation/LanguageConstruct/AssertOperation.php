<?php

namespace Webgraphe\Phlip\Operation\LanguageConstruct;

use SebastianBergmann\GlobalState\RuntimeException;
use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\ExpressionContract;
use Webgraphe\Phlip\ExpressionList;
use Webgraphe\Phlip\Operation\LanguageConstruct;

class AssertOperation extends LanguageConstruct
{
    const IDENTIFIER = 'assert';

    /**
     * @param ContextContract $context
     * @param ExpressionList $expressions
     * @return mixed
     * @throws \RuntimeException
     */
    protected function invoke(ContextContract $context, ExpressionList $expressions)
    {
        $form = $expressions->assertHeadExpression();

        return self::assertTruthy($form->evaluate($context), $form);
    }

    /**
     * @param $something
     * @param ExpressionContract|null $form
     * @return mixed
     * @throws \RuntimeException
     */
    private static function assertTruthy($something, ExpressionContract $form)
    {
        if (!$something) {
            throw new RuntimeException('Assertion failed; ' . ($form ?: 'null'));
        }

        return $something;
    }
    /**
     * @return string[]
     */
    public function getIdentifiers(): array
    {
        return [self::IDENTIFIER];
    }
}

<?php

namespace Webgraphe\Phlip\Context;

use Webgraphe\Phlip\Context;
use Webgraphe\Phlip\Operation;

class PhlipyContext extends Context
{
    public function __construct(Context $parent = null)
    {
        parent::__construct($parent);

        $this->defineOperation(new Operation\LanguageConstruct\DefineOperation);
        $this->defineOperation(new Operation\LanguageConstruct\DefinedOperation);
        $this->defineOperation(new Operation\LanguageConstruct\LetOperation);
        $this->defineOperation(new Operation\LanguageConstruct\SetOperation);
        $this->defineOperation(new Operation\LanguageConstruct\GetOperation);
        $this->defineOperation(new Operation\LanguageConstruct\LambdaOperation);
        $this->defineOperation(new Operation\LanguageConstruct\AtomOperation);
        $this->defineOperation(new Operation\LanguageConstruct\CarOperation);
        $this->defineOperation(new Operation\LanguageConstruct\CdrOperation);
        $this->defineOperation(new Operation\LanguageConstruct\ConsOperation);
        $this->defineOperation(new Operation\LanguageConstruct\Logic\AndOperation);
        $this->defineOperation(new Operation\LanguageConstruct\Logic\OrOperation);
        $this->defineOperation(new Operation\LanguageConstruct\Logic\IfOperation);
        $this->defineOperation(new Operation\LanguageConstruct\Structures\DictionaryOperation);

        $this->defineOperation(new Operation\Arithmetic\AdditionOperation);
        $this->defineOperation(new Operation\Arithmetic\SubtractionOperation);
        $this->defineOperation(new Operation\Arithmetic\MultiplicationOperation);
        $this->defineOperation(new Operation\Arithmetic\DivisionOperation);
        $this->defineOperation(new Operation\Arithmetic\ModuloOperation);
        $this->defineOperation(new Operation\Arithmetic\RemainderOperation);

        $this->defineOperation(new Operation\Comparison\EqualOperation);
        $this->defineOperation(new Operation\Comparison\NotEqualOperation);
        $this->defineOperation(new Operation\Comparison\GreaterThanOperation);
        $this->defineOperation(new Operation\Comparison\GreaterThanOrEqualToOperation);
        $this->defineOperation(new Operation\Comparison\LesserThanOperation);
        $this->defineOperation(new Operation\Comparison\LesserThanOrEqualToOperation);
        $this->defineOperation(new Operation\Comparison\SpaceshipOperation);

        $this->defineOperation(new Operation\Logic\NotOperation);
        $this->defineOperation(new Operation\Logic\XorOperation);

        $this->defineOperation(new Operation\Structures\ListOperation);

        $this->define('null', null);
        $this->define('true', true);
        $this->define('false', false);
    }

    private function defineOperation(Operation $operation)
    {
        array_map(
            function (string $identifier) use ($operation) {
                $this->define($identifier, $operation);
            },
            $operation->getIdentifiers()
        );
    }

    public function withAssert(): PhlipyContext
    {
        $this->defineOperation(new Operation\LanguageConstruct\AssertOperation);

        return $this;
    }
}

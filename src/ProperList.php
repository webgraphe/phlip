<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\Exception\EvaluationException;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class ProperList implements FormContract, \Countable
{
    use AssertsStaticType;

    /** @var FormContract[] */
    private $forms;

    public function __construct(FormContract ...$forms)
    {
        $this->forms = $forms;
    }

    public static function asList(FormContract $form): ProperList
    {
        return $form instanceof ProperList
            ? $form
            : new ProperList($form);
    }

    public function getHead(): ?FormContract
    {
        return $this->forms[0] ?? null;
    }

    /**
     * @return FormContract
     * @throws AssertionException
     */
    public function assertHead(): FormContract
    {
        $head = $this->getHead();
        if (!$head) {
            throw new AssertionException("List is empty");
        }

        return $head;
    }

    public function getTail(): ProperList
    {
        return new ProperList(...array_slice($this->forms, 1, null, false));
    }

    /**
     * @return FormContract[]
     */
    public function all(): array
    {
        return $this->forms;
    }

    public function __toString(): string
    {
        return '('
            . implode(
                ' ',
                array_map(
                    function (FormContract $form) {
                        return (string)$form;
                    },
                    $this->forms
                )
            )
            . ')';
    }

    /**
     * @param ContextContract $context
     * @return mixed
     * @throws EvaluationException
     */
    public function evaluate(ContextContract $context)
    {
        if (!$this->getHead()) {
            return null;
        }

        $callable = self::assertCallable($context, $this->assertHead());
        $arguments = $callable instanceof PrimaryOperationContract
            ? array_merge([$context], $this->getTail()->all())
            : array_map(
                function (FormContract $form) use ($context) {
                    return $form->evaluate($context);
                },
                $this->getTail()->forms
            );

        try {
            return call_user_func($callable, ...$arguments);
        } catch (AssertionException $assertion) {
            throw EvaluationException::fromForm($this, 'Evaluation failed', 0, $assertion);
        }
    }

    /**
     * @param ContextContract $context
     * @param FormContract $form
     * @return callable
     * @throws AssertionException
     */
    protected static function assertCallable(ContextContract $context, FormContract $form): callable
    {
        if (!is_callable($thing = $form->evaluate($context))) {
            $type = is_object($thing) ? get_class($thing) : gettype($thing);
            throw new AssertionException("Not a callable; got '$type' from $form");
        }

        return $thing;
    }

    public function count(): int
    {
        return count($this->forms);
    }

    public function equals(FormContract $against): bool
    {
        return $against instanceof static
            && count($this->forms) === count($against->forms)
            && count($this->forms) === count(
                array_filter(
                    array_map(
                        function (FormContract $left, $right) {
                            return $left->equals($right);
                        },
                        $this->forms,
                        $against->forms
                    )
                )
            );
    }
}

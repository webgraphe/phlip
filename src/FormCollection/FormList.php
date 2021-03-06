<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormCollectionContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\ManualOperationContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class FormList extends FormCollection
{
    use AssertsStaticType;

    /** @var FormContract[] */
    private $forms;

    final public function __construct(FormContract ...$forms)
    {
        $this->forms = $forms;
    }

    public static function asList(FormContract $form): FormList
    {
        if ($form instanceof FormList) {
            return $form;
        }

        return new FormList($form);
    }

    public function getHead(): ?FormContract
    {
        return $this->forms[0] ?? null;
    }

    /**
     * @param string $message
     * @return FormContract
     * @throws AssertionException
     */
    public function assertHead($message = "List has no head"): FormContract
    {
        if ($head = $this->getHead()) {
            return $head;
        }

        throw new AssertionException($message);
    }

    public function getTailHead(): ?FormContract
    {
        return $this->forms[1] ?? null;
    }

    /**
     * @param string $message
     * @return FormContract
     * @throws AssertionException
     */
    public function assertTailHead($message = "List's tail is empty"): FormContract
    {
        if ($head = $this->getTailHead()) {
            return $head;
        }

        throw new AssertionException($message);
    }

    public function getTail(): FormList
    {
        return new FormList(...array_slice($this->forms, 1, null, false));
    }

    /**
     * @return FormContract[]
     */
    public function all(): array
    {
        return $this->forms;
    }

    /**
     * @param ScopeContract $scope
     * @return mixed
     * @throws AssertionException
     */
    public function evaluate(ScopeContract $scope)
    {
        if (!$this->getHead()) {
            return null;
        }

        $callable = static::assertCallable($scope, $this->getHead());
        $tailForms = $this->getTail()->all();
        $arguments = $callable instanceof ManualOperationContract
            ? array_merge([$scope], $tailForms)
            : array_map(
                function (FormContract $form) use ($scope) {
                    return $scope->execute($form);
                },
                $tailForms
            );

        return call_user_func($callable, ...$arguments);
    }

    /**
     * @param ScopeContract $scope
     * @param FormContract $form
     * @return callable
     * @throws AssertionException
     */
    protected static function assertCallable(ScopeContract $scope, FormContract $form): callable
    {
        if (!is_callable($thing = $scope->execute($form))) {
            $type = is_object($thing) ? get_class($thing) : gettype($thing);

            throw new AssertionException("Not a callable; got '$type' from $form");
        }

        return $thing;
    }

    public function count(): int
    {
        return count($this->forms);
    }

    public function isEmpty(): bool
    {
        return !$this->forms;
    }

    public function getOpeningSymbol(): Opening
    {
        return Opening\OpenListSymbol::instance();
    }

    public function getClosingSymbol(): Closing
    {
        return Closing\CloseListSymbol::instance();
    }

    /**
     * @param callable $callback
     * @return FormCollectionContract|static
     */
    public function map(callable $callback): FormCollectionContract
    {
        return new static(...array_map($callback, $this->all()));
    }
}

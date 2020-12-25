<?php

namespace Webgraphe\Phlip\FormCollection;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormCollectionContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\PrimaryOperationContract;
use Webgraphe\Phlip\Exception\AssertionException;
use Webgraphe\Phlip\FormCollection;
use Webgraphe\Phlip\Symbol\Closing;
use Webgraphe\Phlip\Symbol\Opening;
use Webgraphe\Phlip\Traits\AssertsStaticType;

class ProperList extends FormCollection
{
    use AssertsStaticType;

    /** @var FormContract[] */
    private $forms;

    final public function __construct(FormContract ...$forms)
    {
        $this->forms = $forms;
    }

    public static function asList(FormContract $form): ProperList
    {
        if ($form instanceof ProperList) {
            return $form;
        }

        return new ProperList($form);
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
    public function assertHead($message = "List is empty"): FormContract
    {
        $head = $this->getHead();
        if (!$head) {
            throw new AssertionException($message);
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

    /**
     * @param ContextContract $context
     * @return mixed
     * @throws AssertionException
     */
    public function evaluate(ContextContract $context)
    {
        if (!$this->getHead()) {
            return null;
        }

        $callable = static::assertCallable($context, $this->getHead());
        $arguments = $callable instanceof PrimaryOperationContract
            ? array_merge([$context], $this->getTail()->all())
            : array_map(
                function (FormContract $form) use ($context) {
                    return $context->execute($form);
                },
                $this->getTail()->forms
            );

        return call_user_func($callable, ...$arguments);
    }

    /**
     * @param ContextContract $context
     * @param FormContract $form
     * @return callable
     * @throws AssertionException
     */
    protected static function assertCallable(ContextContract $context, FormContract $form): callable
    {
        if (!is_callable($thing = $context->execute($form))) {
            $type = is_object($thing) ? get_class($thing) : gettype($thing);

            throw new AssertionException("Not a callable; got '$type' from $form");
        }

        return $thing;
    }

    public function count(): int
    {
        return count($this->forms);
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

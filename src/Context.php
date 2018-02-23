<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Contracts\WalkerContract;
use Webgraphe\Phlip\Exception\ContextException;

class Context implements ContextContract
{
    /** @var array */
    private $data = [];
    /** @var Context */
    private $parent;
    /** @var FormContract[] */
    private $formStack = [];
    /** @var WalkerContract  */
    private $walker;
    /** @var int */
    private $ticks = 0;

    public function __construct(FormBuilder $formBuilder = null)
    {
        $this->walker = new Walker($this, $formBuilder);
    }

    /**
     * @param string $key $offset
     * @param mixed $value
     * @return mixed
     * @throws ContextException
     */
    public function define($key, $value)
    {
        if ($this->parent) {
            return $this->parent->define($key, $value);
        }

        if (array_key_exists($key, $this->data)) {
            throw new ContextException("Can't redefine global '$key'");
        }

        return $this->data[$key] = $value;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed
     * @throws ContextException
     */
    public function set($offset, $value)
    {
        if (array_key_exists($offset, $this->data)) {
            $previous = $this->data[$offset];
            $this->data[$offset] = $value;

            return $previous;
        }

        if ($this->parent) {
            return $this->parent->set($offset, $value);
        }

        throw new ContextException("Undefined '$offset'");
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     * @throws ContextException
     */
    public function let($key, $value)
    {
        if (array_key_exists($key, $this->data)) {
            throw new ContextException("Can't redefine local '$key'");
        }

        return $this->data[$key] = $value;
    }

    /**
     * @param $offset
     * @return mixed|null
     * @throws ContextException
     */
    public function get($offset)
    {
        if (array_key_exists($offset, $this->data)) {
            return $this->data[$offset];
        }

        if ($this->parent) {
            return $this->parent->get(...func_get_args());
        }

        throw new ContextException("Undefined '$offset'");
    }

    public function has(string $key): bool
    {
        if (array_key_exists($key, $this->data)) {
            return true;
        }

        if ($this->parent) {
            return $this->parent->has($key);
        }

        return false;
    }

    public function stack(): ContextContract
    {
        $self = new self;
        $self->parent = $this;

        return $self;
    }

    /**
     * @param FormContract $form
     * @return mixed
     */
    public function execute(FormContract $form)
    {
        $form = call_user_func($this->walker, $form);
        $this->formStack[] = $form;
        $result = $this->tick($form)->evaluate($this);
        array_pop($this->formStack);

        return $result;
    }

    /**
     * @return FormContract[]
     */
    public function getFormStack(): array
    {
        return $this->formStack;
    }

    /**
     * @return ContextContract
     */
    public function getParent(): ?ContextContract
    {
        return $this->parent;
    }

    public function tick(FormContract $form): FormContract
    {
        if ($parent = $this->getParent()) {
            $parent->tick($form);
        }

        ++$this->ticks;

        return $form;
    }

    public function getTicks(): int
    {
        return $this->ticks;
    }
}

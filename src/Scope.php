<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ScopeContract;
use Webgraphe\Phlip\Contracts\FormContract;
use Webgraphe\Phlip\Exception\ScopeException;

class Scope implements ScopeContract
{
    /** @var array */
    private $data = [];
    /** @var ScopeContract|null */
    private $parent;
    /** @var FormContract[] */
    private $formStack = [];
    /** @var int */
    private $ticks = 0;

    /**
     * @param string $key $offset
     * @param mixed $value
     * @return mixed
     * @throws ScopeException
     */
    public function define(string $key, $value)
    {
        if ($this->parent) {
            return $this->parent->define($key, $value);
        }

        if (array_key_exists($key, $this->data)) {
            throw new ScopeException("Can't redefine global '$key'");
        }

        return $this->data[$key] = $value;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     * @throws ScopeException
     */
    public function set(string $key, $value)
    {
        if (array_key_exists($key, $this->data)) {
            $previous = $this->data[$key];
            $this->data[$key] = $value;

            return $previous;
        }

        if ($this->parent) {
            return $this->parent->set($key, $value);
        }

        throw new ScopeException("Undefined '$key'");
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     * @throws ScopeException
     */
    public function let(string $key, $value)
    {
        if (array_key_exists($key, $this->data)) {
            throw new ScopeException("Can't redefine local '$key'");
        }

        return $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws ScopeException
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if ($this->parent) {
            return $this->parent->get(...func_get_args());
        }

        throw new ScopeException("Undefined '$key'");
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

    public function stack(): ScopeContract
    {
        $self = new static();
        $self->parent = $this;

        return $self;
    }

    /**
     * @param FormContract $form
     * @return mixed
     * @throws ScopeException
     */
    public function execute(FormContract $form)
    {
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
     * @return ScopeContract
     */
    public function getParent(): ?ScopeContract
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

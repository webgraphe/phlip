<?php

namespace Webgraphe\Phlip;

use Webgraphe\Phlip\Contracts\ContextContract;
use Webgraphe\Phlip\Exception\ContextException;

class Context implements ContextContract
{
    private $data = [];
    /** @var Context */
    private $parent;

    public function __construct(ContextContract $parent = null)
    {
        $this->parent = $parent;
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
}

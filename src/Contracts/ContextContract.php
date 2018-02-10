<?php

namespace Webgraphe\Phlip\Contracts;

/**
 * A multi-layered dictionary.
 */
interface ContextContract
{
    /**
     * Defines a dictionary entry at the topmost level (a level with no parent).
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function define($key, $value);

    /**
     * Sets the value for an existing dictionary entry at the closest level possible.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set($key, $value);

    /**
     * Defines a dictionary entry at the lowest level.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function let($key, $value);

    /**
     * Retrieves a dictionary entry at the closest level possible.
     *
     * @param $offset
     * @return mixed
     */
    public function get($offset);

    /**
     * Tells if a dictionary entry exists at any level.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Stacks the current context and return a new instance pushed on top of it.
     * @return ContextContract
     */
    public function stack(): ContextContract;

    /**
     * @param FormContract $form
     * @return mixed
     */
    public function execute(FormContract $form);

    public function tick(FormContract $form): FormContract;

    public function getTicks(): int;

    /**
     * @return FormContract[]
     */
    public function getFormStack(): array;

    public function getParent(): ?ContextContract;
}

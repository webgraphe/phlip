<?php

namespace Webgraphe\Phlip\Contracts;

use Webgraphe\Phlip\Exception\ScopeException;

/**
 * A multi-layered dictionary.
 */
interface ScopeContract
{
    /**
     * Defines a dictionary entry at the topmost level (a level with no parent).
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function define(string $key, $value);

    /**
     * Sets the value for an existing dictionary entry at the closest level possible.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, $value);

    /**
     * Defines a dictionary entry at the lowest level.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function let(string $key, $value);

    /**
     * Retrieves a dictionary entry at the closest level possible.
     *
     * @param $key
     * @return mixed
     * @throws ScopeException If not found
     */
    public function get($key);

    /**
     * Tells if a dictionary entry exists at any level.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Stacks the current scope and return a new instance pushed on top of it.
     *
     * @return ScopeContract
     */
    public function stack(): ScopeContract;

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

    public function getParent(): ?ScopeContract;
}

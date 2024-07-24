<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface for classes that implement session functionality.
 */
interface SessionInterface
{
    /**
     * Starts a session if it has not already been started. Throws an exception if a session has already
     * been started or if headers have already been sent.
     *
     * @throws SessionException If a session has already been started or if headers have already been sent.
     */
    public function start(): void;

    /**
     * Saves the session and closes the session file.
     */
    public function save(): void;

    /**
     * Checks if the session is active.
     *
     * @return bool True if the session is active, false otherwise.
     */
    public function isActive(): bool;

    /**
     * Gets a session value by key.
     *
     * @param string $key   The key to look up.
     * @param mixed  $default The value to return if $key is not found.
     *
     * @return mixed The value, or $default if not found.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Update the current session id with a newly generated one
     *
     * @return bool True if the session ID was regenerated, false otherwise.
     */
    public function regenerate(): bool;

    /**
     * Sets the value of a session variable.
     *
     * @param string $key The name of the session variable
     * @param mixed $value The value to set the session variable to
     */
    public function put(string $key, mixed $value): void;

    /**
     * Deletes a session variable.
     *
     * @param string $key The name of the session variable to delete
     */
    public function forget(string $key): void;
}

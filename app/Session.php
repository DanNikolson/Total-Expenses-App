<?php

declare(strict_types=1);

namespace App;

use App\Contracts\SessionInterface;
use App\DataObjects\SessionConfig;
use App\Exception\SessionException;

/**
 * Implementation of the SessionInterface, providing methods to start and save sessions.
 *
 * @package App
 */
class Session implements SessionInterface
{
    /**
     * Session constructor.
     *
     * Initializes the session.
     */
    public function __construct(private readonly SessionConfig $options)
    {
    }

    /**
     * Starts the session if it has not already been started.
     *
     * @throws SessionException if the session has already been started or if headers have been sent.
     * Sets session cookie params.
     * Sets session name.
     * Starts session, @throws SessionException if the session could not be started.
     */
    public function start(): void
    {
        if ($this->isActive()) {
            throw new SessionException('Session has already been started');
        }

        if (headers_sent($filename, $line)) {
            throw new SessionException('Headers have already been sent by ' . $filename . ':' . $line);
        }

        session_set_cookie_params(
            [
                'secure' => $this->options->secure,
                'httponly' => $this->options->httpOnly,
                'samesite' => $this->options->sameSite->value,
            ]
        );
        if (!empty($this->options->name)) {
            session_name($this->options->name);
        }

        if (!session_start()) {
            throw new SessionException('Unable to start the session');
        };
    }

    /**
     * Saves the session data to persistent storage.
     */
    public function save(): void
    {
        session_write_close();
    }

    /**
     * Checks whether the session is currently active.
     *
     * @return bool true if the session is active, false otherwise
     */
    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Retrieves the value of a session variable, or a default value if the variable does not exist.
     *
     * @param string $key The name of the session variable
     * @param mixed $default The default value to return if the session variable does not exist
     * @return mixed The value of the session variable, or the default value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $_SESSION[$key] : $default;
    }

    /**
     * Checks whether a session variable exists.
     *
     * @param string $key The name of the session variable
     * @return bool true if the session variable exists, false otherwise
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Update the current session id with a newly generated one
     *
     * @return bool true if the session ID was regenerated, false otherwise
     */
    public function regenerate(): bool
    {
        return session_regenerate_id();
    }

    /**
     * Sets the value of a session variable.
     *
     * @param string $key The name of the session variable
     * @param mixed $value The value to set the session variable to
     */
    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function forget(string $key): void
    /**
     * Deletes a session variable.
     *
     * @param string $key The name of the session variable to delete
     */
    {
        unset($_SESSION[$key]);
    }

    /**
     * Flashes a message to the session with optional custom messages.
     *
     * @param string $key The main message to flash
     * @param array $messages Additional messages to flash
     */
    public function flash(string $key, array $messages): void
    {
        $_SESSION[$this->options->flashName][$key] = $messages;
    }

    /**
     * Retrieves flash messages stored under a given key.
     *
     * This method retrieves flash messages from the session and removes them from the session.
     *
     * @param string $key The key to retrieve the flash messages from
     * @return array The flash messages stored under the given key, or an empty array if no messages are stored
     */

    public function getFlash(string $key): array
    {
        $messages = $_SESSION[$this->options->flashName][$key] ?? [];

        unset($_SESSION[$this->options->flashName][$key]);

        return $messages;
    }
}

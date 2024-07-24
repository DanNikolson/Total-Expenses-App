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
}

<?php

declare(strict_types=1);

namespace App;

use App\Contracts\SessionInterface;
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
    public function __construct()
    {
    }

    /**
     * Starts the session if it has not already been started.
     *
     * @throws SessionException if the session has already been started or if headers have been sent.
     */
    public function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException('Session has already been started');
        }

        if (headers_sent($filename, $line)) {
            throw new SessionException('Headers have already been sent');
        }

        session_set_cookie_params(['secure' => true, 'httponly' => true, 'samesite' => 'lax']);

        session_start();
    }

    /**
     * Saves the session data to persistent storage.
     */
    public function save(): void
    {
        session_write_close();
    }
}

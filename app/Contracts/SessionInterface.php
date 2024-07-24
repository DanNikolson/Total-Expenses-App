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
}

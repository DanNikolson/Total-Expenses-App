<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface UserInterface
 *
 * Represents a user in the application.
 *
 * @package App\Contracts
 */
interface UserInterface
{
    /**
     * Returns the user's unique identifier.
     *
     * @return int The user's unique identifier.
     */
    public function getId(): int;

    /**
     * Returns the user's password.
     *
     * @return string The user's password.
     */
    public function getPassword(): string;
}

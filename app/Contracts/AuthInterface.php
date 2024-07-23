<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface for classes that implement authentication functionality.
 */
interface AuthInterface
{
    /**
     * Returns the currently authenticated user.
     *
     * @return UserInterface|null The authenticated user, or null if no user is authenticated.
     */
    public function user(): ?UserInterface;

    /**
     * Attempts to login with the provided data.
     *
     * @param array $data The login data.
     * @return bool True if login attempt was successful, false otherwise.
     */
    public function attemptLogin(array $credentials): bool;

    /**
     * Checks the provided credentials against the given user's credentials.
     *
     * @param UserInterface $user The user to check credentials against.
     * @param array $credentials The credentials to check.
     * @return bool True if the provided credentials match the user's credentials, false otherwise.
     */
    public function checkCredentials(UserInterface $user, array $credentials): bool;

    /**
     * Logs the user out.
     *
     * Clears any authentication related data and sets the user to null.
     *
     * @return void
     */
    public function logOut(): void;
}

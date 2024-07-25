<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DataObjects\RegisterUserData;

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

    /**
     * Registers a new user with the provided data.
     *
     * @param RegisterUserData $data The data to register the user with.
     * @return UserInterface The newly registered user.
     */
    public function register(RegisterUserData $data): UserInterface;

    /**
     * Logs in the provided user.
     *
     * This method sets the user as the currently authenticated user and clears any
     * authentication related data.
     *
     * @param UserInterface $user The user to log in.
     * @return void
     */
    public function logIn(UserInterface $user): void;
}

<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface for classes that provide user-related services.
 */
interface UserProviderServiceInterface
{
    /**
     * Get a user by their ID.
     *
     * @param int $userId The user ID.
     * @return UserInterface|null The user associated with the ID, or null if not found.
     */
    public function getById(int $userId): ?UserInterface;

    /**
     * Get a user by their credentials.
     *
     * @param array $credentials The credentials to search for.
     * @return UserInterface|null The user associated with the credentials, or null if not found.
     */
    public function getByCredentials(array $credentials): ?UserInterface;
}

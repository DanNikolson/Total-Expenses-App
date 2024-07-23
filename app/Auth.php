<?php

declare(strict_types=1);

namespace App;

use App\Contracts\AuthInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;

/**
 * This class implements the AuthInterface and provides functionality for user authentication.
 * It uses the Doctrine EntityManager to retrieve the user entity from the database.
 */
class Auth implements AuthInterface
{
    /**
     * The currently authenticated user.
     *
     * @var ?UserInterface
     */
    private ?UserInterface $user = null;

    /**
     * Constructor for the Auth class.
     *
     * @param UserProviderServiceInterface $userProvider The service used to retrieve users from the database.
     */
    public function __construct(private readonly UserProviderServiceInterface $userProvider)
    {
    }

    /**
     * Returns the currently authenticated user.
     *
     * @return UserInterface|null The authenticated user, or null if no user is authenticated.
     */
    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $_SESSION['user'] ?? null;

        if (!$userId) {
            return null;
        }

        $user = $this->userProvider->getById($userId);

        if (!$user) {
            return null;
        }

        $this->user = $user;
        return $this->user;
    }

    /**
     * Attempts to login with the provided data.
     *
     * @param array $data The login data.
     * @return bool True if login attempt was successful, false otherwise.
     */
    public function attemptLogin(array $credentials): bool
    {
        $user = $this->userProvider->getByCredentials($credentials);

        if (!$user || !$this->checkCredentials($user, $credentials)) {
            return false;
        }

        session_regenerate_id();

        $_SESSION['user'] = $user->getId();

        $this->user = $user;

        return true;
    }

    /**
     * Checks if the provided credentials match the user's credentials.
     *
     * @param UserInterface $user The user to check the credentials against.
     * @param array $credentials The credentials to check.
     * @return bool True if the credentials match, false otherwise.
     */
    public function checkCredentials(UserInterface $user, array $credentials): bool
    {
        return password_verify($credentials['password'], $user->getPassword());
    }

    /**
     * Logs out the current user.
     *
     * @return void
     */
    public function logOut(): void
    {
        unset($_SESSION['user']);

        $this->user = null;
    }
}

<?php

declare(strict_types=1);

namespace App;

use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
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
    public function __construct(
        private readonly UserProviderServiceInterface $userProvider,
        private readonly SessionInterface $session
    ) {
    }


    /**
     * Returns the currently authenticated user.
     *
     * This method checks if a user is already authenticated by checking if the 'user' session variable is set.
     * If a user is authenticated, it returns the user entity. If not, it retrieves the user from the database
     * using the user provider service and sets the user entity. If the user is not found, it returns null.
     *
     * @return UserInterface|null The authenticated user, or null if no user is authenticated.
     */
    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get('user');

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
     * This method retrieves the user from the user provider service based on the provided credentials.
     * If the user is not found or the credentials do not match, the method returns false.
     * Otherwise, it updates the session ID with the new one, stores the user ID in the session, and sets the authenticated user.
     *
     * @param array $credentials The login data.
     * @return bool True if login attempt was successful, false otherwise.
     */
    public function attemptLogin(array $credentials): bool
    {
        $user = $this->userProvider->getByCredentials($credentials);

        if (!$user || !$this->checkCredentials($user, $credentials)) {
            return false;
        }

        $this->session->regenerate();
        $this->session->put('user', $user->getId());

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
     * Logs the user out.
     *
     * Clears the 'user' session variable and regenerates the session id.
     * Sets the authenticated user to null.
     *
     * @return void
     */
    public function logOut(): void
    {
        $this->session->forget('user');
        $this->session->regenerate();

        $this->user = null;
    }

    public function register(array $data): UserInterface
    {
        $user = $this->userProvider->createUser($data);

        //Authenticate user

        return $user;
    }
}

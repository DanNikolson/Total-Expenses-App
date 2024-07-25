<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Contracts\UserInterface;
use App\DataObjects\RegisterUserData;
use App\Contracts\UserProviderServiceInterface;

/**
 * This class implements the UserProviderServiceInterface and provides functionality for user retrieval.
 * It uses the Doctrine EntityManager to retrieve the user entity from the database.
 */
class UserProviderService implements UserProviderServiceInterface
{
    /**
     *
     * @param EntityManager $entityManager The Doctrine EntityManager used to retrieve the user entity from the database.
     */
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /**
     * Retrieves a user by their ID.
     *
     * @param int $userId The ID of the user to retrieve.
     * @return UserInterface|null The user with the specified ID, or null if no user is found.
     */
    public function getById(int $userId): ?UserInterface
    {
        return $this->entityManager->find(User::class, $userId);
    }

    /**
     * Retrieves a user by their credentials.
     *
     * @param array $credentials An associative array containing the user's credentials.
     * @return UserInterface|null The user with the specified credentials, or null if no user is found.
     */
    public function getByCredentials(array $credentials): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
    }

    /**
     * Creates a new user with the provided data and persists it to the database.
     *
     * @param array $data An associative array containing the user's data.
     *                    The array should have the following keys:
     *                    - name: The user's name.
     *                    - email: The user's email address.
     *                    - password: The user's password.
     * @return UserInterface The newly created user.
     */
    public function createUser(RegisterUserData $data): UserInterface
    {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(password_hash(
            $data['password'],
            PASSWORD_BCRYPT,
            ['cost' => 12]
        ));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}

<?php

declare(strict_types=1);

namespace App;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Contracts\AuthInterface;
use App\Contracts\UserInterface;

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
     * @param EntityManager $entityManager The Doctrine EntityManager used to retrieve the user entity from the database.
     */
    public function __construct(private readonly EntityManager $entityManager)
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

        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return null;
        }

        $this->user = $user;
        return $this->user;
    }
}

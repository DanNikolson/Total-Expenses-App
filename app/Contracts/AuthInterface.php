<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface for classes that implement authentication functionality.
 *
 */
interface AuthInterface
{
    /**
     * Returns the currently authenticated user.
     *
     * @return UserInterface|null The authenticated user, or null if no user is authenticated.
     */
    public function user(): ?UserInterface;
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Entity\Category;
use Doctrine\ORM\EntityManager;

/**
 * CategoryService is a class that provides functionality for creating categories.
 *
 * @package App\Services
 */
class CategoryService
{
    /**
     * CategoryService constructor.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager used to persist and flush the category.
     */

    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /**
     * Creates a new category.
     *
     * @param string $name The name of the category.
     * @param User $user The user associated with the category.
     * @return Category The newly created category.
     */
    public function create(string $name, User $user): Category
    {
        $category = new Category();

        $category->setName($name);
        $category->setUser($user);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}

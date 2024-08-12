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

    public function __construct(private readonly EntityManager $entityManager) {}

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

    /**
     * Retrieves all categories from the database.
     *
     * This function uses the Doctrine EntityManager to retrieve all categories from the database.
     *
     * @return array An array of Category objects representing all categories in the database.
     */

    public function getAll(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    /**
     * Deletes a category from the database.
     *
     * This function uses the Doctrine EntityManager to remove a category from the database.
     *
     * @param int $id The ID of the category to be deleted.
     * @return void
     */
    public function delete(int $id): void
    {
        $category = $this->entityManager->find(Category::class, $id);

        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}

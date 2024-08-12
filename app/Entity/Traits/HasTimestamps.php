<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * This trait is used to add created_at and updated_at fields to an entity.
 *
 * It automatically updates the updated_at field on both insert and update operations.
 * If the entity is newly created, it also sets the created_at field.
 */
trait HasTimestamps
{
    /**
     * @var DateTime The creation date of the entity
     * @Column(name="created_at")
     */
    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    /**
     * @var DateTime The last update date of the entity
     * @Column(name="updated_at")
     */

    #[Column(name: 'updated_at')]
    private DateTime $updatedAt;

    /**
     * Updates the created_at and updated_at fields before persisting or updating the entity.
     *
     * @PrePersist
     * @PreUpdate
     */

    #[PrePersist, PreUpdate]
    public function updateTimestamps(LifecycleEventArgs $args): void
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new DateTime();
        }
        $this->updatedAt = new DateTime();
    }
}

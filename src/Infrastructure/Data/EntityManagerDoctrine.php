<?php

declare(strict_types=1);

namespace App\Infrastructure\Data;

use App\Domain\Data\DomainEntityManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class EntityManagerDoctrine implements DomainEntityManagerInterface
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }
    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function clear(): void
    {
        $this->entityManager->clear();
    }
}

<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\LootPool;

/**
 * @extends ServiceEntityRepository<DropPool>
 */
class LootPoolRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, LootPool::class);
    }

    public function save(LootPool $lootPool): void
    {
        $this->em->persist($lootPool);
        $this->em->flush();
    }

    public function delete(LootPool $lootPool): void
    {
        $this->em->remove($lootPool);
        $this->em->flush();
    }
}

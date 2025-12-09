<?php

namespace App\Repository;

use App\Entity\DropPool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<DropPool>
 */
class DropPoolRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, DropPool::class);
    }

    public function save(DropPool $dropPool): void
    {
        $this->em->persist($dropPool);
        $this->em->flush();
    }
}

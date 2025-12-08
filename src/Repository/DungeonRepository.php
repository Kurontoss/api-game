<?php

namespace App\Repository;

use App\Entity\Dungeon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Dungeon>
 */
class DungeonRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, Dungeon::class);
    }

    public function save(Dungeon $dungeon): void
    {
        $this->em->persist($dungeon);
        $this->em->flush();
    }

    public function delete(Dungeon $dungeon): void
    {
        $this->em->remove($dungeon);
        $this->em->flush();
    }
}

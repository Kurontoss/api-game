<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Enemy;

/**
 * @extends ServiceEntityRepository<Enemy>
 */
class EnemyRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, Enemy::class);
    }

    public function save(Enemy $enemy): void
    {
        $this->em->persist($enemy);
        $this->em->flush();
    }

    public function delete(Enemy $enemy): void
    {
        $this->em->remove($enemy);
        $this->em->flush();
    }
}

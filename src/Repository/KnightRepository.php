<?php

namespace App\Repository;

use App\Entity\Knight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Knight>
 */
class KnightRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, Knight::class);
    }

    public function save(Knight $knight): void
    {
        $this->em->persist($knight);
        $this->em->flush();
    }

    public function delete(Knight $knight): void
    {
        $this->em->remove($knight);
        $this->em->flush();
    }
}

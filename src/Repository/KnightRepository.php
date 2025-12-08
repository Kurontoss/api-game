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
    private $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Knight::class);
        $this->em = $em;
    }

    public function save(Knight $knight)
    {
        $this->em->persist($knight);
        $this->em->flush();
    }

    public function delete(Knight $knight)
    {
        $this->em->remove($knight);
        $this->em->flush();
    }
}

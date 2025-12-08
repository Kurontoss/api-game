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
    private $em;
    
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Dungeon::class);
        $this->em = $em;
    }

    public function save(Dungeon $dungeon)
    {
        $this->em->persist($dungeon);
        $this->em->flush();
    }

    public function delete(Dungeon $dungeon)
    {
        $this->em->remove($dungeon);
        $this->em->flush();
    }
}
